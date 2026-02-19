<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'transaction_reference' => 'TST202860000xxxxx',
            'user_id' => 1,
            'organization_id' => 1,
            'transaction_id' => md5((string)now()),
            'payment_type_id' => 1,
            'payment_method_id' => 1,
            'amount' => 1000,
            'currency' => 'EGP',
            'rate' => 1,
            'action' => 'pay',
            'services' => '{"id":"1234567","description":"any description"}',
            'customer' => '{"name":"name","email":"mail@email.com","phone":"12345678987","street1":"street 1","city":"cairo","state":"cai","country":"Eg"}',
            'client_key' => md5(now() . rand(0, 10000)),
        ];
    }
}
