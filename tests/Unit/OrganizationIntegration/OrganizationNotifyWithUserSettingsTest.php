<?php

namespace Tests\Unit\OrganizationIntegration;


use App\Jobs\OrganizationNotifyWithUserSettingsJob;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrganizationNotifyWithUserSettingsTest extends TestCase
{
    /**
     * @return void
     */
    public function testCanNotifyOrganizationWithUserSettingsInCaseToken(): void
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

        $job = new OrganizationNotifyWithUserSettingsJob($user);
        $job->handle();
        $this->assertTrue(TRUE);

    }

    /**
     * @return void
     */
    public function testCanNotifyOrganizationWithUserSettingsInCaseBackOffice(): void
    {
        $user = User::first();
        $user->system_configuration = [
            "auth_type" => "backOffice",
            "login_url" => "google.com",
            "agent" => "agent",
            "notification_url" => "google.com.test"
        ];

        Http::fake([
            $user->system_configuration['notification_url'] => Http::response([]),
        ]);

        $job = new OrganizationNotifyWithUserSettingsJob($user);
        $job->handle();
        $this->assertTrue(TRUE);

    }

    /**
     * @return array[]
     */
    private function mockAuthDataInCaseToken(): array
    {
        return [
            'data' => [
                'access_token' => Str::random(50)
            ]
        ];
    }
}
