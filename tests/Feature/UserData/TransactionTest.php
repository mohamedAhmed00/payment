<?php

namespace Tests\Feature\UserData;

use App\Models\Status;
use App\Models\Transaction;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function testUserCanGetHisTransactionByClientKey()
    {
        $transaction = Transaction::factory()->create(['amount' => 3, 'payment_type_id' => 2]);
        $status = Status::first();
        $transaction->statuses()->sync($status->id);
        $response = $this->get(route('user.transaction', $transaction->client_key));
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    [
                        'amount', 'currency', 'rate', 'action', 'client_key', 'services' => ['id', 'description'],
                        'customer' => ['name', 'email', 'phone', 'street1', 'city', 'state', 'country'],
                        'transaction_reference', 'transaction_id', 'payment_type', 'payment_method',
                        'statuses' => [['name', 'key', 'created_at']], 'created_at'
                    ]
                ],
            ]
        );
    }
}
