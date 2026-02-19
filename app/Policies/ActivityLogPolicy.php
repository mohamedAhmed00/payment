<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Services\Interfaces\IPermissionService;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct(private IPermissionService $permissionService)
    {
    }

    public function viewAny() : bool
    {
        return $this->permissionService->checkPermission('activity_log-index');
    }
}
