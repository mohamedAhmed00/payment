<?php

namespace Tests\Feature\Integration\online\Payfort;

use App\Models\Organization;
use App\Models\OrganizationSupplier;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class RefundTest extends TestCase
{

    /**
     * @return void
     */
    public function testUserCanRefundWithPayfort(): void
    {
        Http::fake([
            'https://sbpaymentservices.payfort.com/FortAPI/paymentApi' . '*' => Http::response(
                getJsonFileContentAsArray('mock/suppliers/payfort/online/refund_200.json'),
                200,
                []
            )
        ]);
        $supplier = Supplier::where('key', 'payfort')->first();

        $organizationObject = Organization::factory()->create();
        $organizationObject->suppliers()->attach($supplier->id, ['settings' => json_encode([
            'supplier_sha_response_phrase' => Str::random(10),
            'name' => 'payfort',
            'supplier_refund_integration_url' => "https://sbpaymentservices.payfort.com/FortAPI/paymentApi/",
            'supplier_access_code' => Str::random(10),
            'supplier_merchant_identifier' => Str::random(10),
            'supplier_sha_request_phrase' => Str::random(10)
        ])]);
        $user = User::factory()->create([
            'organization_supplier_id' => $organizationObject->suppliers->first()->id
        ]);
        $this->actingAs($user);
        $transaction = Transaction::factory()->create(['amount' => 100, 'payment_type_id' => 1, 'payment_method_id' => 1, 'user_id' => $user->id]);
        $response = $this->post('/api/refund',
            [
                'amount' => rand(1,10),
                'reason' => Str::random(20),
                'transaction_id' => $transaction->transaction_id,
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'payment_type',
                    'status',
                    'client_key',
                    'message',
                    'signature',
                    'transaction_id',
                ],
            ]
        );
        $this->assertEquals('online', $response->json()['data']['payment_type']);
        $this->assertEquals($transaction->client_key, $response->json()['data']['client_key']);
        $this->assertEquals('refunded', $response->json()['data']['status']);
        $this->assertEquals('Success', $response->json()['data']['message']);
    }
}
