<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\DTO\FormGroupDTO;
use App\Domain\Responder\Interfaces\IHttpRedirectResponder;
use App\Domain\Services\Interfaces\IGroupService;
use App\Http\Requests\FormGroupRequest;
use App\Models\Group;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GroupController extends Controller
{

    public function __construct(private IGroupService $groupService, private IHttpRedirectResponder $httpResponder)
    {
    }

    public function index() : View | Factory | Application
    {
        $this->authorize('viewAny', [Group::class]);

        return $this->httpResponder->response('dashboard.group.index', [
            'groups' => $this->groupService->getGroupsWithPagination(),
        ]);
    }

    public function create() : Renderable | View
    {
        $this->authorize('create', [Group::class]);

        return $this->httpResponder->response('dashboard.group.form');
    }

    public function store(FormGroupRequest $request) : RedirectResponse
    {
        $this->authorize('create', [Group::class]);

        return $this->httpResponder->redirect($this->groupService->storeUserGroup((array) FormGroupDTO::fromRequest($request)));
    }

    public function show(Group $group) : View|Renderable
    {
        $this->authorize('view', Group::class);

        return $this->httpResponder->response('dashboard.group.show', compact('group'));
    }

    public function edit(Group $group) : Response | View
    {
        $this->authorize('update', [Group::class, $group]);

        return $this->httpResponder->response('dashboard.group.form', ['group' => $this->groupService->getUserGroup($group->id)]);
    }

    public function update(FormGroupRequest $request, Group $group) : RedirectResponse
    {
        $this->authorize('update', [Group::class, $group]);

        return $this->httpResponder->redirect($this->groupService->updateUserGroup($group->id, (array) FormGroupDTO::fromRequest($request)));
    }

    public function destroy(Group $group) : RedirectResponse
    {
        $this->authorize('delete', [Group::class, $group]);

        return $this->httpResponder->redirect($this->groupService->deleteGroup($group->id));
    }
}
