<?php

namespace Tests\Feature\UserData;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTypesTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetHisPaymentTypes()
    {
        $response = $this->get(route('user.payment.settings'));
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    [
                        'name',
                        'key',
                        'payment_methods'
                    ]
                ]
            ]
        );
    }
}
