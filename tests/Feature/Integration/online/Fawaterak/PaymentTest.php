<?php

namespace Tests\Feature\Integration\online\Fawaterak;

use App\Models\Organization;
use App\Models\PaymentType;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function testUserCanPayWithFawaterak()
    {
        Transaction::truncate();
        $user = User::factory()->create();
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

        $user->organization_supplier_id = $organizationObject->suppliers->first()->id;
        $user->paymentTypes()->attach(PaymentType::where('key', 'online')->first());
        $user->paymentMethod()->attach(PaymentType::where('key', 'online')->first());
        $user->save();
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
        $this->assertEquals('redirect', $response->json()['data']['action']['type']);
    }

    private function preparePaymentData($currency = 'EGP')
    {
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
            'items' => [
                [
                    'price' => 500,
                    'quantity' => 2,
                    'name' => Str::random(10)
                ]
            ],
            'client_key' => md5(now())
        ];
        $data['signature'] = hash('sha256', prepareDataBeforeMakeSignature($data, auth()->user()->signature_key));
        return $data;
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
                'errors',
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
                'message',
                'errors',
            ]
        );
        $this->assertEquals($response->json()['message'], __("No supplier assigned to this user"));
    }

    public function testItemsAttributeIsRequired()
    {
        Transaction::truncate();
        $user = User::factory()->create();
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

        $user->organization_supplier_id = $organizationObject->suppliers->first()->id;
        $user->paymentTypes()->attach(PaymentType::where('key', 'online')->first());
        $user->paymentMethod()->attach(PaymentType::where('key', 'online')->first());
        $user->save();
        $data = $this->preparePaymentData();
        $data['items'] = null;
        unset($data['signature']);
        $data['signature'] = hash('sha256', prepareDataBeforeMakeSignature($data, auth()->user()->signature_key));
        $response = $this->post('/api/pay', $data);
        $response->assertJsonStructure([
            'message',
            'errors' => ['items'],
        ]);
    }

    public function testItemsPriceAttributeIsRequired()
    {
        Transaction::truncate();
        $user = User::factory()->create();
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

        $user->organization_supplier_id = $organizationObject->suppliers->first()->id;
        $user->paymentTypes()->attach(PaymentType::where('key', 'online')->first());
        $user->paymentMethod()->attach(PaymentType::where('key', 'online')->first());
        $user->save();
        $data = $this->preparePaymentData();
        $data['items'][]['price'] = null;
        unset($data['signature']);
        $data['signature'] = hash('sha256', prepareDataBeforeMakeSignature($data, auth()->user()->signature_key));
        $response = $this->post('/api/pay', $data);
        $response->assertJsonStructure([
            'message',
            'errors' => ['items.1.price'],
        ]);
    }

    public function testItemsNameAttributeIsRequired()
    {
        Transaction::truncate();
        $user = User::factory()->create();
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

        $user->organization_supplier_id = $organizationObject->suppliers->first()->id;
        $user->paymentTypes()->attach(PaymentType::where('key', 'online')->first());
        $user->paymentMethod()->attach(PaymentType::where('key', 'online')->first());
        $user->save();
        $data = $this->preparePaymentData();
        $data['items'][]['name'] = null;
        unset($data['signature']);
        $data['signature'] = hash('sha256', prepareDataBeforeMakeSignature($data, auth()->user()->signature_key));
        $response = $this->post('/api/pay', $data);
        $response->assertJsonStructure([
            'message',
            'errors' => ['items.1.name'],
        ]);
    }

    public function testItemsQuantityAttributeIsRequired()
    {
        Transaction::truncate();
        $user = User::factory()->create();
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

        $user->organization_supplier_id = $organizationObject->suppliers->first()->id;
        $user->paymentTypes()->attach(PaymentType::where('key', 'online')->first());
        $user->paymentMethod()->attach(PaymentType::where('key', 'online')->first());
        $user->save();
        $data = $this->preparePaymentData();
        $data['items'][]['quantity'] = null;
        unset($data['signature']);
        $data['signature'] = hash('sha256', prepareDataBeforeMakeSignature($data, auth()->user()->signature_key));
        $response = $this->post('/api/pay', $data);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'items.1.quantity'
            ],
        ]);
    }
}
