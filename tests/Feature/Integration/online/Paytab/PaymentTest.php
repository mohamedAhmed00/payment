<?php

namespace Tests\Feature\Integration\online\Paytab;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function testUserCanSendPaymentRequest()
    {
        Transaction::truncate();
        Http::fake([
            $this->paytabsUrl . '*' => Http::response(
                getJsonFileContentAsArray('mock/suppliers/paytab/online/response_200.json'),
                200,
                []
            )
        ]);
        $response = $this->post('/api/pay', array_merge($this->preparePaymentData()));
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'action' => ['content', 'type'],
                    'type',
                    'status',
                    'signature',
                    'client_key',
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

    public function testUserCanRefundWithOnlinePayment()
    {
        Http::fake([
            $this->paytabsUrl . '*' => Http::response(
                getJsonFileContentAsArray('mock/suppliers/paytab/online/refund_200.json'),
                200,
                []
            )
        ]);
        $transaction = Transaction::factory()->create(['amount' => 100, 'payment_type_id' => 1, 'payment_method_id' => 1]);
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
                    'signature',
                    'transaction_id',
                ],
            ]
        );
        $this->assertEquals('online', $response->json()['data']['payment_type']);
        $this->assertEquals($transaction->client_key, $response->json()['data']['client_key']);
        $this->assertEquals('refunded', $response->json()['data']['status']);
        $this->assertEquals('Authorised', $response->json()['data']['message']);
    }

    public function testNotActiveOrganizationUserCanRefundTransaction()
    {
        $organization = auth()->user()->organization;
        $organization->status = 0;
        $organization->save();
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 1]);
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

    public function testUserCanSendPaymentRequestWithoutConfigurePaymentSuppliers()
    {
        $user = auth()->user();
        $user->supplierSettings()->dissociate();
        $user->save();
        $response = $this->post('/api/pay', array_merge($this->preparePaymentData()));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(
            [
                'message' ,
                'errors',
            ]
        );
        $this->assertEquals($response->json()['message'], __("No supplier assigned to this user"));
    }

    public function testNonOrganizationUserCanSendPaymentRequest()
    {
        auth()->user()->organization_id = null;
        auth()->user()->save();
        $response = $this->post('/api/pay', array_merge($this->preparePaymentData()));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __("This user doesnt have organization"));
    }

    public function testUserCanSendPaymentRequestWithOutSignature()
    {
        $data = array_merge($this->preparePaymentData());
        unset($data['signature']);
        $response = $this->post('/api/pay', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(
            [
                'message' ,
                'errors' => [
                    'signature'
                ],
            ]
        );
        $this->assertEquals($response->json()['message'], __("The signature field is required."));
    }

    public function testUserCanSendPaymentRequestWithInvalidSignature()
    {
        $data = array_merge($this->preparePaymentData());
        $data['signature'] .= $data['signature'] . 'any';
        $response = $this->post('/api/pay', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(
            [
                'message' ,
                'errors' => [
                    'signature'
                ],
            ]
        );
        $this->assertEquals($response->json()['message'], __("Request body not correct"));
    }

    public function testUserCanRefundTransactionThatNotBelongsToHim()
    {
        $this->actingAs(User::first()->replicate());
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 1]);
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

    public function testUserCanRefundAmountHigherThanPaidAmount()
    {
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 1]);
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

    public function testUserMustPayWithTheSameCurrencyThatUserBeforeInTheSameTransaction(){
        $data = $this->preparePaymentData();
        Transaction::factory()->create(['amount' => 3, 'currency' => 'USD', 'payment_type_id' => 1, 'client_key' => $data['client_key']]);
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
        Transaction::factory()->create(['amount' => 3,'payment_type_id' => 1]);
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

    public function testUserMustConfigureHisPaymentSuppliers(){
        $user = auth()->user();
        $user->supplierSettings()->dissociate();
        $user->save();
        Transaction::factory()->create(['amount' => 3,'payment_type_id' => 1]);
        $response = $this->post('/api/pay', $this->preparePaymentData());
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __('No supplier assigned to this user'));
    }

    public function testUserMustConfigureHisPaymentMethods(){
        auth()->user()->paymentMethod()->detach();
        Transaction::factory()->create(['amount' => 3,'payment_type_id' => 1]);
        $response = $this->post('/api/pay', $this->preparePaymentData());
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __('Payment method not configured for this user'));
    }

    public function testUserMustMustPayWithSupplierCurrency(){
        $data = $this->preparePaymentData('USD');
        Transaction::factory()->create(['amount' => 3,'payment_type_id' => 1]);
        $response = $this->post('/api/pay', $data);
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'errors' ,
                'message',
            ]
        );
        $this->assertEquals($response->json()['message'], __('Not allowed currency'));
    }

    /**
     * @return void
     */
    public function testPaymentMethodRequiredIfPaymentTypeIsOnline()
    {
        $paymentData = $this->preparePaymentData();
        $data = data_set($paymentData, 'payment_method', '');
        $response = $this->post('/api/pay', $data);
        $response->assertJsonStructure([
            'message',
            'errors' => ['payment_method'],
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function preparePaymentData($currency = 'EGP'){
        $data = [
            'amount' => 1000,
            'currency' => $currency,
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
        $data['signature'] = makeDigitalSignature($data, auth()->user()->signature_key);
        return $data;
    }
}
