<?php

namespace Tests\Feature\Integration\online\Paytab;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CallbackTest extends TestCase
{
    public function testPaytabCanMakeCallbackToNotifyPaymentStatus()
    {
        $user = User::first();
        $user->system_configuration = [
            "origin" => "google.com.origin",
            "password" => "test",
            "username" => "test_user",
            "auth_type" => "token",
            "login_url" => "google.com",
            "notification_url" => "google.com.test"
        ];
        $user->save();
        Http::fake([
            $user->system_configuration['notification_url'] => Http::response([]),
            $user->system_configuration['login_url'] => Http::response($this->mockAuthDataInCaseToken(), Response::HTTP_OK, []),
        ]);

        $transaction = Transaction::factory()->create();
        $data =  array_merge($this->prepareCallbackData());
        $response = $this->post('/callback', $data);
        $response->assertStatus(200);
        $transaction->refresh();
        $this->assertEquals('paid', $transaction->statuses->last()->key);
    }

    private function prepareCallbackData()
    {
        return [
            "supplier" => 'paytab',
            "method" => 'online',
            "token" => auth()->user()->generateToken()->token,
            "tran_ref" => "TST202860000xxxxx",
            "cart_id" => "cart_11111",
            "cart_description" => "Description of the items",
            "cart_currency" => "SAR",
            "cart_amount" => "12.3",
            "customer_details" => [
                "name" => "wajih last",
                "email" => "wajih@domain.com",
                "phone" => "0522222222",
                "street1" => "address street",
                "city" => "dubai",
                "state" => "DU",
                "country" => "AE",
                "ip" => "92.98.175.138"
            ],
            "shipping_details" => [
                "name" => "wajih last1",
                "email" => "email1@domain.com",
                "phone" => "971555555555",
                "street1" => "street2",
                "city" => "dubai",
                "state" => "DU",
                "country" => "AE",
                "ip" => "92.98.175.138"
            ],
            "payment_result" => [
                "response_status" => "A",
                "response_code" => "G77803",
                "response_message" => "Authorised",
                "transaction_time" => "2020-10-12T04:43:23Z"
            ],
            "payment_info" => [
                "card_type" => "Credit",
                "card_scheme" => "Visa",
                "payment_description" => "4111 11## #### 1111"
            ]
        ];
    }

    private function mockAuthDataInCaseToken(): array
    {
        return [
            'data' => [
                'access_token' => Str::random(50)
            ]
        ];
    }
}
