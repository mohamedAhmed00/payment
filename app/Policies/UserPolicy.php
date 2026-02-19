<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Services\Interfaces\IPermissionService;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function __construct(private IPermissionService $permissionService)
    {
    }

    public function viewAny() : bool
    {
        return $this->permissionService->checkPermission('user_management-index');
    }

    public function view(User $user, User $model) : bool
    {
        return $this->authorizeUserByGroup($user, $model, 'user_management-update');
    }

    public function create() : bool
    {
        return $this->permissionService->checkPermission('user_management-store');
    }

    public function update(User $user, User $model) : bool
    {
        return $this->authorizeUserByGroup($user, $model, 'user_management-update');
    }

    public function delete(User $user, User $model) : bool
    {
        return $this->authorizeUserByGroup($user, $model, 'user_management-update');
    }

    public function paymentSettings(User $user, User $model) : bool
    {
        return $this->authorizeUserByGroup($user, $model, 'user_management-paymentSettings');
    }

    public function savePaymentSettings(User $user, User $model) : bool
    {
        return $this->authorizeUserByGroup($user, $model, 'user_management-savePaymentSettings');
    }

    public function listAuthTransactions(User $user, User $model): bool
    {
        return auth()?->user()?->id === $model?->id || auth()->user()?->organization_id == null;
    }

    private function authorizeUserByGroup(User $user, User $model, $key) : bool
    {
        if ($this->permissionService->checkPermission($key)) {
            if ($user->group_id) {
                $userGroup = $user->group;
                $modelGroup = $model->group;

                return $userGroup->level <= $modelGroup->level;
            }

            return true;
        }

        return false;
    }
}
