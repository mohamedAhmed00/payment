<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Services\Interfaces\IPermissionService;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignPermissionPolicy
{
    use HandlesAuthorization;

    /**
     * AssignPermissionPolicy constructor.
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
        return $this->permissionService->checkPermission('permission-index');
    }

    /**
     * @return bool
     */
    public function view() : bool
    {
        return $this->permissionService->checkPermission('permission-index');
    }

    /**
     * @return bool
     */
    public function create() : bool
    {
        return $this->permissionService->checkPermission('permission-store');
    }

    /**
     * @param  User  $user
     * @param  Group $group
     * @return bool
     */
    public function update(User $user, Group $group) : bool
    {
        return $this->authorizeUserByGroup($user, $group, 'permission-update');
    }

    /**
     * @param User  $user
     * @param Group $group
     * @param $key
     * @return bool
     */
    private function authorizeUserByGroup(User $user, Group $group, $key) : bool
    {
        if ($this->permissionService->checkPermission($key)) {
            if ($user->group_id) {
                return $user->level <= $group->id;
            }

            return true;
        }

        return false;
    }
}
