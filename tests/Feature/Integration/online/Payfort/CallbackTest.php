<?php

namespace Tests\Feature\Integration\online\Payfort;

use App\Models\Organization;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CallbackTest extends TestCase
{

    public const MERCHANT_REFERENCE = 'ABCD-123';
    public const ROUTE_NAME = 'returning';
    public const PAYED_AMOUNT = 1000;


    /**
     * @return void
     */
    public function testPayfortCanMakeCallbackToNotifyPaymentStatus(): void
    {
        $user = $this->prepareAuthUserSettings();
        Http::fake([
            $user->system_configuration['notification_url'] => Http::response([]),
            $user->system_configuration['login_url'] => Http::response($this->mockAuthDataInCaseToken(), Response::HTTP_OK, []),
        ]);
        $transaction = Transaction::factory()->create([
            'transaction_id' => self::MERCHANT_REFERENCE,
            'amount' => self::PAYED_AMOUNT,
            'user_id' => $user->id
        ]);
        $data = array_merge($this->prepareReturningData());
        $response = $this->post('/callback', $data);
        $response->assertStatus(200);
        $transaction->refresh();
        $this->assertEquals('paid', $transaction->statuses->last()->key);
    }

    private function prepareAuthUserSettings()
    {
        Transaction::truncate();
        $supplier = Supplier::where('key', 'payfort')->first();

        $organizationObject = Organization::factory()->create();
        $organizationObject->suppliers()->attach($supplier->id, ['settings' => json_encode([
            'supplier_sha_response_phrase' => Str::random(10),
        ])]);
        $user = User::factory()->create([
            'organization_supplier_id' => $organizationObject->suppliers->first()->id
        ]);
        $this->actingAs($user);

        $user->system_configuration = [
            "origin" => "google.com.origin",
            "password" => "test",
            "username" => "test_user",
            "auth_type" => "token",
            "login_url" => "google.com",
            "notification_url" => "google.com.test"
        ];
        $user->save();
        return $user;
    }

    private function mockAuthDataInCaseToken(): array
    {
        return [
            'data' => [
                'access_token' => Str::random(50)
            ]
        ];
    }

    /**
     * @return array
     */
    public function prepareReturningData(): array
    {
        $date = [
            "response_code" => "02000",
            "merchant_reference" => self::MERCHANT_REFERENCE,
            "fort_id" => "TST202860000xxxxx",
            "amount" => self::PAYED_AMOUNT * 100,
            "status" => 12000
        ];
        $date['signature'] = $this->generteSignature($date);
        return array_merge($date, [
            "supplier" => "payfort"
        ]);
    }

    private function generteSignature(array $data)
    {
        $payfortSetting = json_decode(auth()->user()->supplierSettings->settings, true);
        ksort($data);
        $shaString = '';
        foreach ($data as $key => $value) {
            $shaString .= "$key=$value";
        }
        return hash('sha256', $payfortSetting['supplier_sha_response_phrase'] . $shaString . $payfortSetting['supplier_sha_response_phrase']);
    }
}
