<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Services\Interfaces\IPermissionService;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * GroupPolicy constructor.
     * @param IPermissionService $permissionService
     */
    public function __construct(private IPermissionService $permissionService)
    {
    }

    /**
     * @return bool
     */
    public function viewAny() : bool
    {
        return $this->permissionService->checkPermission('organization-index');
    }

    /**
     * @param  User         $user
     * @param  Organization $organization
     * @return bool
     */
    public function view(User $user, Organization $organization) : bool
    {
        return $this->authorizeUserByOrganization($user, $organization, 'organization-show');
    }

    /**
     * @param  User $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        return $this->permissionService->checkPermission('organization-store') && empty($user->organization_id);
    }

    /**
     * @param  User         $user
     * @param  Organization $organization
     * @return bool
     */
    public function update(User $user, Organization $organization) : bool
    {
        return $this->authorizeUserByOrganization($user, $organization, 'organization-update');
    }

    /**
     * @param  User $user
     * @return bool
     */
    public function delete(User $user) : bool
    {
        return $this->permissionService->checkPermission('organization-destroy') && empty($user->organization_id);
    }

    /**
     * @param  User         $user
     * @param  Organization $organization
     * @return bool
     */
    public function settings(User $user, Organization $organization) : bool
    {
        return $this->authorizeUserByOrganization($user, $organization, 'organization-settings');
    }

    /**
     * @param User         $user
     * @param Organization $organization
     * @param $key
     * @return bool
     */
    private function authorizeUserByOrganization(User $user, Organization $organization, $key) : bool
    {
        if ($this->permissionService->checkPermission($key)) {
            if ($user->organization_id) {
                return $user->organization_id == $organization->id;
            }

            return true;
        }

        return false;
    }
}
