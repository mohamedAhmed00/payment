<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\DTO\UpdateGroupPermissionDTO;
use App\Domain\Responder\Interfaces\IHttpRedirectResponder;
use App\Domain\Services\Interfaces\IPermissionService;
use App\Http\Requests\UpdatePermission;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{

    public function __construct(private readonly IPermissionService $permissionService, private readonly IHttpRedirectResponder $httpResponder)
    {
    }

    public function index(Group $group) : View | Renderable
    {
        $this->authorize('view', [Permission::class]);

        return $this->httpResponder->response('dashboard.group.permissions', $this->permissionService->getGroupPermissions($group));
    }

    public function store() : RedirectResponse
    {
        $this->authorize('create', Permission::class);

        return $this->httpResponder->redirect(
            $this->permissionService->getAllPermissions()
        );
    }

    public function update(UpdatePermission $request, Group $group) : RedirectResponse
    {
        $this->authorize('update', [Permission::class, $group]);

        return $this->httpResponder->redirect(
            $this->permissionService->updatePermissions($group, (array) UpdateGroupPermissionDTO::fromRequest($request))
        );
    }
}
