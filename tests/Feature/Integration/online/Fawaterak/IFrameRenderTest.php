<?php

namespace Tests\Feature\Integration\online\Fawaterak;

use App\Domain\Provider\Fawaterak\Enum\Statuses;
use App\Models\Organization;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class IFrameRenderTest extends TestCase
{
    public function testCanRenderIFrameToPayWithFawaterak()
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
        $data = array_merge($this->prepareReturningData($organizationObject->suppliers->first()));
        $response = $this->get(route('render_iframe', $data));
        $response->assertStatus(200);
    }

    private function prepareReturningData($supplierSetting): array
    {
        $transaction = Transaction::factory()->create();
        $transaction->statuses()->attach(Status::where('key', 'processing')->first());
        $dataToBeCached = [
            'envType' => 'test',
            'hashKey' => Str::random(20),
            'requestBody' => [
                'cartTotal' => 300,
                'currency' => 'EGP',
                'customer' => [
                    'first_name' => Str::random(10),
                    'last_name' => Str::random(10),
                    'email' => Str::random(10) . '@gmail.com',
                    'phone' => Str::random(10),
                    'address' => Str::random(10),
                ],
                'redirectionUrls' => [
                    'successUrl' => route('returning', ['supplier' => 'fawaterak', 'status' => Statuses::SUCCESS]),
                    'failUrl' => route('returning', ['supplier' => 'fawaterak', 'status' => Statuses::SUCCESS]),
                    'pendingUrl' => route('returning', ['supplier' => 'fawaterak', 'status' => Statuses::SUCCESS])
                ],
                'cartItems' => [[
                    'price' => 200,
                    'quantity' => 2,
                    'name' => Str::random(10)
                ]]
            ],
            'plugin' => config('suppliers.fawaterak.' . $supplierSetting['env_type'] . '.plugin')
        ];
        $signature_key = Str::random(10);
        Cache::put($signature_key, $dataToBeCached);
        return [
            "supplier" => "fawaterak",
            "transaction_reference" => $transaction->transaction_id,
            "signature_key" => $signature_key
        ];
    }

    public function testIfTransactionIsPaidLinkWillExpired()
    {
        Transaction::truncate();
        $user = User::first();
        $this->actingAs($user);
        $transaction = Transaction::factory()->create();
        $transaction->statuses()->attach(Status::where('key', '!=', 'processing')->first());
        $data = [
            'signature_key' => Str::random(5),
            "supplier" => "fawaterak",
            "transaction_reference" => $transaction->transaction_id,
        ];
        $response = $this->get(route('render_iframe', $data));
        $response->assertJsonStructure([
            'message',
            'errors' => ['signature_key'],
        ]);
    }

    public function testIfIFrameDataNotExistInCacheLinkWillExpired()
    {
        Transaction::truncate();
        $user = User::first();
        $this->actingAs($user);
        $transaction = Transaction::factory()->create();
        $transaction->statuses()->attach(Status::where('key', '!=', 'processing')->first());
        Cache::flush();
        $data = [
            'signature_key' => Str::random(5),
            "supplier" => "fawaterak",
            "transaction_reference" => $transaction->transaction_id,
        ];
        $response = $this->get(route('render_iframe', $data));
        $response->assertJsonStructure([
            'message',
            'errors' => ['signature_key'],
        ]);

    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }
}
