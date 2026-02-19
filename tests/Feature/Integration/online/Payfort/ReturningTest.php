<?php

namespace Tests\Feature\Integration\online\Payfort;

use App\Models\Organization;
use App\Models\OrganizationSupplier;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReturningTest extends TestCase
{
    public const MERCHANT_REFERENCE = 'ABCD-123';
    public const ROUTE_NAME = 'returning';
    public const PAYED_AMOUNT= 1000;

    /**
     * @return void
     */
    public function testPayfortCanReturnToOurSystemAfterPaymentDone(): void
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

        $transaction = Transaction::factory()->create([
            'transaction_id' => self::MERCHANT_REFERENCE,
            'amount' => self::PAYED_AMOUNT,
            'user_id' => auth()->id()
        ]);
        $data =  array_merge($this->prepareReturningData());
        $response = $this->post(route(self::ROUTE_NAME, $data));
        $response->assertStatus(302);
        $transaction->refresh();
        $this->assertEquals('paid', $transaction->statuses->last()->key);
    }

    /**
     * @return array
     */
    public function prepareReturningData(): array
    {
        $date =  [
            "response_code" => "02000",
            "merchant_reference" => self::MERCHANT_REFERENCE,
            "fort_id" => "TST202860000xxxxx",
            "amount" => self::PAYED_AMOUNT * 100,
            "status" => 12000
        ];
        $date['signature'] = $this->generateSignature($date);
        return array_merge($date, [
            "supplier" => "payfort"
        ]);
    }

    /**
     * @param array $data
     * @return false|string
     */
    private function generateSignature(array $data): bool|string
    {
        $payfortSetting = json_decode(auth()->user()->supplierSettings->settings, true);
        ksort($data);
        $shaString = '';
        foreach ($data as $key => $value) {
            $shaString .= "$key=$value";
        }
        return hash('sha256', optional($payfortSetting)['supplier_sha_response_phrase'] . $shaString . optional($payfortSetting)['supplier_sha_response_phrase']);
    }
}
