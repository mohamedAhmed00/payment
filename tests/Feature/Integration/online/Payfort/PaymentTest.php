<?php

namespace Tests\Feature\Integration\online\Payfort;

use App\Models\Organization;

use App\Models\PaymentType;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public const ROUTE_NAME = 'pay';

    /**
     * @return void
     */
    public function testUserCanPayThroughPayfort()
    {
        $supplier = Supplier::where('key', 'payfort')->first();

        $organizationObject = Organization::factory()->create();
        $organizationObject->suppliers()->attach($supplier->id, ['settings' => json_encode([
            'supplier_sha_response_phrase' => Str::random(10),
            'name' => 'payfort',
            'supplier_pay_integration_url' => "https://sbpaymentservices.payfort.com/FortAPI/paymentApi/",
            'supplier_access_code' => Str::random(10),
            'supplier_merchant_identifier' => Str::random(10),
            'supplier_sha_request_phrase' => Str::random(10),
            'supplier_currency' => 'EGP'
        ])]);
        $user = User::factory()->create([
            'organization_supplier_id' => $organizationObject->suppliers->first()->id
        ]);
        $user->paymentTypes()->attach(PaymentType::where('key', 'online')->first());
        $user->paymentMethod()->attach(PaymentType::where('key', 'online')->first());

        $this->actingAs($user);
        $response = $this->json('post', route(self::ROUTE_NAME), $this->preparePaymentData());
        $response->assertJsonStructure([
            'data' => [
                'action' => [
                    'content',
                     'type'
                ],
                'type',
                'status',
                'client_key',
                'transaction_id',
                'signature',
                'message'
            ]
        ]);
        $this->assertEquals('form', $response->json()['data']['action']['type']);

    }

    /**
     * @return array
     */
    private function preparePaymentData(): array
    {
        $data = [
            'amount' => 1000,
            'currency' => 'EGP',
            'rate' => 1,
            'payment_type' => 'online',
            'payment_method' => 'online',
            'services' => [
                'id' => '1234567',
                'description' => 'any description'
            ],
            'customer' => [
                'name' => 'name',
                'email' => 'mail@email.com',
                'phone' => '12345678987',
                'street1' => 'street 1',
                'city' => 'cairo',
                'state' => 'cai',
                'country' => 'Eg',
            ],
            'client_key' => md5(now())
        ];
        $data['signature'] = hash('sha256', prepareDataBeforeMakeSignature($data, auth()->user()->signature_key));
        return $data;
    }
}
