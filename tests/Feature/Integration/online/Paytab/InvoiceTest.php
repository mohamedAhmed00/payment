<?php

namespace Tests\Feature\Integration\online\Paytab;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    public function testUserCanPayWithInvoice()
    {
        Transaction::truncate();
        Http::fake([
            $this->paytabsUrl . '*' => Http::response(
                getJsonFileContentAsArray('mock/suppliers/paytab/invoice/response_200.json'),
                200,
                []
            )
        ]);
        Mail::fake();
        $response = $this->post('/api/pay', array_merge($this->preparePaymentData()));
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'action' => ['content', 'type'],
                    'type',
                    'message',
                    'status',
                    'signature',
                    'client_key',
                ],
            ]
        );
    }

    public function testInvoiceMustBeInvalidTotal()
    {
        $organization = auth()->user()->organization;
        $organization->status = 0;
        $organization->save();
        $response = $this->post('/api/pay', $this->preparePaymentData());
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __("This organization is de-active, activate it first"));
    }

    private function preparePaymentData($currency = 'EGP'){
        $data = [
            'amount' => 9.5,
            'currency' => $currency,
            'rate' => 1,
            'payment_type' => 'online',
            'payment_method' => 'invoice_with_mail',
            'services' => [
                'id' => '1234567',
                'description' => 'any description'
            ],
            'customer' => [
                'name' => 'name',
                'email' => 'admin@paymenthub.com',
                'phone' => '12345678987',
                'street1' => 'street 1',
                'city' => 'cairo',
                'state' => 'cai',
                'country' => 'Eg',
            ],
            "invoice" => [
                "shipping_charges" => 0,
                "extra_charges" => 0,
                "extra_discount" => 0,
                "total" => 0,
                "expiry_date" => "2022-11-28T13:33:00+04:00",
                "due_date" => "2022-11-26T12:36:00+04:00",
                "line_items" => [
                    [
                        "sku" => "sku",
                        "description" => "desc",
                        "url" => "https://www.costacoffee.ae/whats-new/flat-white",
                        "unit_cost"=> 9.5,
                        "quantity"=> 1,
                        "net_total" => 9.5,
                        "discount_rate" => 0,
                        "discount_amount" => 0,
                        "tax_rate" => 0,
                        "tax_total" => 0,
                        "total" => 9.5
                    ]
                ]
            ],
            'client_key' => md5(now())
        ];
        $data['signature'] = makeDigitalSignature($data, auth()->user()->signature_key);
        return $data;
    }
}
