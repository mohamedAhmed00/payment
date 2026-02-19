<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\DTO\StoreUserDTO;
use App\Domain\Repositories\Interfaces\IUserRepository;
use App\Domain\Responder\Interfaces\IHttpRedirectResponder;
use App\Domain\Services\Interfaces\IGroupService;
use App\Domain\Services\Interfaces\IOrganizationService;
use App\Domain\Services\Interfaces\IUserService;
use App\Http\Requests\UpdateUserPaymentSettingsRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class UserManagementController extends Controller
{

    public function __construct(
        private readonly IUserService           $userService,
        private readonly IHttpRedirectResponder $httpResponder,
        private readonly IGroupService          $groupService,
        private readonly IOrganizationService   $organizationService
    )
    {
    }

    public function index(): View|Renderable
    {
        $this->authorize('viewAny', [User::class]);

        return $this->httpResponder->response('dashboard.user.index', [
            'users' => $this->userService->getUsersWithPagination((int)request()->get('page')),
        ]);
    }

    public function create(): View|Renderable
    {
        $this->authorize('create', [User::class]);

        return $this->httpResponder->response('dashboard.user.form', [
            'groups' => $this->groupService->getAllGroups(),
            'organizations' => $this->organizationService->getAllOrganizations(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('create', [User::class]);

        return $this->httpResponder->redirect(
            $this->userService->storeUser((array)StoreUserDTO::fromRequest($request))
        );
    }

    public function edit(User $user): View|Renderable
    {
        $this->authorize('update', [User::class, $user]);

        return $this->httpResponder->response('dashboard.user.form', [
            'user' => $user,
            'groups' => $this->groupService->getAllGroups(),
            'organizations' => $this->organizationService->getAllOrganizations(),
        ]);
    }

    public function update(UserRequest $request, $user): RedirectResponse
    {
        $this->authorize('update', [User::class, resolve(IUserRepository::class)->first(['id' => $user])]);
        return $this->httpResponder->redirect(
            $this->userService->updateProfile((int)$user, array_filter((array)StoreUserDTO::fromRequest($request)))
        );
    }

    public function destroy(User $user): RedirectResponse|Throwable
    {
        $this->authorize('delete', [User::class, $user]);

        return $this->httpResponder->redirect($this->userService->deleteUser($user->id));
    }

    public function paymentSettings(User $user)
    {
        $this->authorize('paymentSettings', [User::class, $user]);
        return $this->httpResponder->response('dashboard.user.payment_settings',
            $this->userService->getUserData($user->id));
    }

    public function savePaymentSettings(UpdateUserPaymentSettingsRequest $request, User $user)
    {
        $this->authorize('savePaymentSettings', [User::class, $user]);
        $this->userService->savePaymentSettings($user->id, $request->validated());

    }

    public function listUserTransactions(User $user): Factory|View|Application
    {
        $this->authorize('listAuthTransactions', [User::class, $user]);
        return view('dashboard.user.list_transactions', [
            'transactions' =>$user?->transactions()->latest()?->with([
                'paymentType',
                'paymentMethod',
                'statuses' => fn($query) => $query->orderBy('id', 'DESC')
                ])->paginate(),
        ]);
    }

}
