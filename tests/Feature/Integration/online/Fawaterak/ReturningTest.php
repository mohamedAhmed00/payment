<?php

namespace Tests\Feature\Integration\online\Fawaterak;

use App\Domain\Provider\Fawaterak\Enum\Statuses;
use App\Models\Organization;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReturningTest extends TestCase
{
    public const PAYED_AMOUNT= 1000;

    public function testFawaterakCanReturnToOurSystemAfterPaymentDone()
    {
        Transaction::truncate();
        $user = User::first();
        $this->actingAs($user);
        $supplier = Supplier::where('key', 'fawaterak')->first();

        $organizationObject = Organization::factory()->create();
        $organizationObject->suppliers()->attach($supplier->id, ['settings' => json_encode([
            'supplier_api_key' => Str::random(10),
            'supplier_provider_key' => Str::random(10),
            'env_type' => 'test',
            'supplier_currency' => 'EGP',
            'name' => 'fawaterak',
        ])]);
        $transaction = Transaction::factory()->create();
        $data =  array_merge($this->prepareReturningData($transaction));
        $response = $this->post(route('returning', $data));
        $response->assertStatus(302);
        $transaction->refresh();
        $this->assertEquals('paid', $transaction->statuses->last()->key);
    }

    private function prepareReturningData($transaction)
    {
        return [
            "supplier" => "fawaterak",
            "invoice_id" => rand(10,20),
            "transaction_reference" => $transaction?->transaction_id,
            "status" => Statuses::SUCCESS
        ];
    }
}
