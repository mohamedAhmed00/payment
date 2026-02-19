<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Services\Interfaces\IPermissionService;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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
        return $this->permissionService->checkPermission('group-index');
    }

    /**
     * @return bool
     */
    public function view() : bool
    {
        return $this->permissionService->checkPermission('group-show');
    }

    /**
     * @return bool
     */
    public function create() : bool
    {
        return $this->permissionService->checkPermission('group-store');
    }

    /**
     * @param  User  $user
     * @param  Group $group
     * @return bool
     */
    public function update(User $user, Group $group) : bool
    {
        return $this->authorizeUserByGroup($user, $group, 'group-update');
    }

    /**
     * @param  User  $user
     * @param  Group $group
     * @return bool
     */
    public function delete(User $user, Group $group) : bool
    {
        return $this->authorizeUserByGroup($user, $group, 'group-destroy');
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
                return $user->group->level <= $group->level;
            }

            return true;
        }

        return false;
    }
}
