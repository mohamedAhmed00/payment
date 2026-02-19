<?php

namespace Tests\Feature\Integration\online\Paytab;

use App\Models\Transaction;
use Tests\TestCase;

class ReturningTest extends TestCase
{
    public const PAYED_AMOUNT= 1000;

    public function testPaytabCanReturnToOurSystemAfterPaymentDone()
    {
        $transaction = Transaction::factory()->create([
            'amount' => self::PAYED_AMOUNT
        ]);
        $data =  array_merge($this->prepareReturningData());
        $response = $this->post(route('returning', $data));
        $response->assertStatus(302);
        $transaction->refresh();
        $this->assertEquals('paid', $transaction->statuses->last()->key);
    }

    private function prepareReturningData()
    {
        return [
            "acquirerMessage" => null,
            "acquirerRRN" => null,
            "cartId" => "1234567",
            "customerEmail" => "mail@email.com",
            "respCode" => "G54163",
            "respMessage" => "Authorised",
            "respStatus" => "A",
            "signature" => "9f2963d5b0e490354fd16a0e3818b934e065aafe01cb8c52d2e891c67b0c3171",
            "tranRef" => "TST202860000xxxxx",
            "supplier" => "paytab",
            "method" => "online",
            "token" => auth()->user()->generateToken()->token,
            "amount" => self::PAYED_AMOUNT
        ];
    }
}
