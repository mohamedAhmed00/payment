<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $paytabsUrl = 'https://secure-egypt.paytabs.com/payment/';

    protected function setUp(): void
    {
        $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        parent::setUp();
        $this->fakeTelegram();
        $this->artisan('migrate:fresh', [
            '--seed' => true,
        ]);
        $user = User::first();
        $user->organization_id = 1;
        $user->signature_key = 'test';
        $user->save();
        $this->actingAs($user);
    }

    protected function fakeTelegram()
    {
        Http::fake([
            config('services.telegram.end_point') . config('services.telegram.api_token') . '/sendMessage' => Http::response([
                'ok' => true,
            ]),
        ]);
    }

    protected function tearDown(): void
    {
        $this->beforeApplicationDestroyed(function () {
            DB::disconnect();
        });
        parent::tearDown();
        Mockery::close();
        gc_collect_cycles();
    }
}
