<?php

namespace Tests\Feature\Integration\online\Payfort;

use App\Models\Organization;
use App\Models\OrganizationSupplier;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    public const ROUTE_NAME = 'pay';

    public function testCanPayPayfortWithInvoice()
    {
        $shaKey = Str::random(10);
        $this->mockResponse($shaKey);

        $this->prepareAuthUserSettings($shaKey);

        $response = $this->json('post', route(self::ROUTE_NAME), $this->preparePaymentData());
        $response->assertJsonStructure([
            'data' => [
                'action',
                'type',
                'status',
                'client_key',
                'transaction_id',
                'signature',
                'message'
            ]
        ]);
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
            'payment_method' => 'invoice_with_mail',
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
        $data['signature'] = makeDigitalSignature($data, auth()->user()->signature_key);
        return $data;
    }

    private function prepareAuthUserSettings($shaKey)
    {
        Transaction::truncate();
        $supplier = Supplier::where('key', 'payfort')->first();

        $organizationObject = Organization::factory()->create();
        $organizationObject->suppliers()->attach($supplier->id, ['settings' => json_encode([
            'supplier_sha_response_phrase' => $shaKey,
            'name' => 'payfort',
            'supplier_pay_integration_url' => 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi/',
            'supplier_refund_integration_url' => 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi/',
            'supplier_access_code' => Str::random(10),
            'supplier_merchant_identifier' => Str::random(10),
            'supplier_sha_request_phrase' => Str::random(10),
            'supplier_currency' => 'EGP'
        ])]);
        $user = User::factory()->create([
            'organization_supplier_id' => $organizationObject->suppliers->first()->id
        ]);
        $user->paymentTypes()->attach(PaymentType::where('key', 'online')->first());
        $user->paymentMethod()->attach(PaymentMethod::where('key', 'invoice_with_mail')->first()->id);
        $this->actingAs($user);
    }

    private function mockResponse(string $shaKey)
    {
        $response = getJsonFileContentAsArray('mock/suppliers/payfort/online/invoice_200.json');
        unset($response['signature']);
        $response['signature'] = generateSignature($response, $shaKey);
        Http::fake([
            'https://sbpaymentservices.payfort.com/FortAPI/paymentApi/*' => Http::response(
                $response,
                200,
                []
            )
        ]);
    }
}
