<?php

namespace Tests\Feature\Integration;

use App\Models\Transaction;
use App\Models\User;
use Tests\TestCase;

class CashTest extends TestCase
{
    public function testUserCanPayWithCashPayment()
    {
        $response = $this->post('/api/pay', $this->preparePaymentData());
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'action' => ['content', 'type'],
                    'type',
                    'status',
                    'client_key',
                    'signature',
                ],
            ]
        );
    }

    public function testNotActiveOrganizationUserCanPay()
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

    public function testUserCanRefundWithCashPayment()
    {
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 2]);
        $response = $this->post('/api/refund',
            [
                'amount' => 2,
                'reason' => " any ",
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
                    'transaction_id',
                    'signature',
                ],
            ]
        );
        $this->assertEquals('cash', $response->json()['data']['payment_type']);
        $this->assertEquals($transaction->client_key, $response->json()['data']['client_key']);
        $this->assertEquals('refunded', $response->json()['data']['status']);
        $this->assertEquals(__('Refunded Successfully'), $response->json()['data']['message']);
    }

    public function testUserCanRefundAmountHigherThanPaidAmount()
    {
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 2]);
        $response = $this->post('/api/refund',
            [
                'amount' => 4,
                'reason' => " any ",
                'transaction_id' => $transaction->transaction_id,
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __("Refund amount must be less or Equal to the paid amount"));
    }

    public function testNotOrganizationUserCanRefundAmount()
    {
        auth()->user()->organization_id = null;
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 2]);
        $response = $this->post('/api/refund',
            [
                'amount' => 2,
                'reason' => " any ",
                'transaction_id' => $transaction->transaction_id,
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __("This user doesnt have organization"));
    }

    public function testUserCanRefundTransactionThatNotBelongsToHim()
    {
        $this->actingAs(User::first()->replicate());
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 2]);
        $response = $this->post('/api/refund',
            [
                'amount' => 2,
                'reason' => " any ",
                'transaction_id' => $transaction->transaction_id,
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __("This user doesnt have permission to refund"));
        User::where('id', '!=', 1)->delete();
    }

    public function testNotActiveOrganizationUserCanRefundTransaction()
    {
        $organization = auth()->user()->organization;
        $organization->status = 0;
        $organization->save();
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 2]);
        $response = $this->post('/api/refund',
            [
                'amount' => 2,
                'reason' => " any ",
                'transaction_id' => $transaction->transaction_id,
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __("This organization is de-active, activate it first"));
    }

    public function testUserCanRefundNotExistTransaction()
    {
        $response = $this->post('/api/refund',
            [
                'amount' => 2,
                'reason' => " any ",
                'transaction_id' => md5(now()),
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'message',
                'errors' => [
                    'transaction_id'
                ]
            ]
        );
        $this->assertEquals($response->json()['message'], __("The selected transaction id is invalid."));
    }

    public function testUserCanRefundWithEmptyTransactionId()
    {
        $response = $this->post('/api/refund',
            [
                'amount' => 2,
                'reason' => " any ",
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'message',
                'errors' => [
                    'transaction_id'
                ]
            ]
        );
        $this->assertEquals($response->json()['message'], __("The transaction id field is required."));
    }

    public function testUserCanRefundWithEmptyAmount()
    {
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 2]);
        $response = $this->post('/api/refund',
            [
                'reason' => " any ",
                'transaction_id' => $transaction->transaction_id,
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'message',
                'errors' => [
                    'amount'
                ]
            ]
        );
        $this->assertEquals($response->json()['message'], __("The amount field is required."));
    }

    public function testUserCanRefundWithStringAmount()
    {
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 2]);
        $response = $this->post('/api/refund',
            [
                'amount' => "2x",
                'reason' => " any ",
                'transaction_id' => $transaction->transaction_id,
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'message',
                'errors' => [
                    'amount'
                ]
            ]
        );
        $this->assertEquals($response->json()['message'], __("The amount must be a number."));
    }

    public function testUserMustPayWithTheSameCurrencyThatUserBeforeInTheSameTransaction(){
        $data = $this->preparePaymentData();
        Transaction::factory()->create(['amount' => 3, 'currency' => 'USD', 'payment_type_id' => 2, 'client_key' => $data['client_key']]);
        $response = $this->post('/api/pay', $data);
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __('Payment amount must be the same currency that paid before'));
    }

    public function testUserMustConfigurePaymentTypesBeforePay(){
        auth()->user()->paymentTypes()->detach();
        Transaction::factory()->create(['amount' => 3,'payment_type_id' => 2]);
        $response = $this->post('/api/pay', $this->preparePaymentData());
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __('Payment type not configured for this user'));
    }

    private function preparePaymentData(){
        $data = [
            'amount' => 1000,
            'currency' => 'EGP',
            'rate' => 1,
            'payment_type' => 'cash',
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
            'client_key' => 'CLIENT_ID'
        ];
        $data['signature'] = makeDigitalSignature($data, auth()->user()->signature_key);
        return $data;
    }
}
