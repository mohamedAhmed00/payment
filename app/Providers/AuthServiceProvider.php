<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\User;
use App\Policies\ActivityLogPolicy;
use App\Policies\AssignPermissionPolicy;
use App\Policies\GroupPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Group::class => GroupPolicy::class,
        Permission::class => AssignPermissionPolicy::class,
        Organization::class => OrganizationPolicy::class,
        ActivityLog::class => ActivityLogPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
