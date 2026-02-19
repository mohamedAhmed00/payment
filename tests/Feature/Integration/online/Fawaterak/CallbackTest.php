<?php

namespace Tests\Feature\Integration\online\Fawaterak;

use App\Models\Organization;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class CallbackTest extends TestCase
{
    public function testFawaterakCanCallbackOurSystem()
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
        $data =  array_merge($this->prepareReturningData());
        $transaction = Transaction::factory()->create(['transaction_reference' => $data['invoice_id']]);

        $response = $this->post(route('callback', $data));
        $response->assertStatus(200);
        $transaction->refresh();
        $this->assertEquals('paid', $transaction->statuses->last()->key);
    }

    private function prepareReturningData(): array
    {
        return [
            "supplier" => "fawaterak",
            "invoice_id" => rand(10,20),
            "invoice_status" => 'paid'
        ];
    }
}
