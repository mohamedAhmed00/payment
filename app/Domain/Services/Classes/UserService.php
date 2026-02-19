<?php

declare(strict_types=1);

namespace App\Domain\Services\Classes;

use App\Domain\Repositories\Interfaces\IGroupRepository;
use App\Domain\Repositories\Interfaces\IUserRepository;
use App\Domain\Services\Interfaces\IUserService;
use App\Exceptions\PermissionException;
use App\Jobs\OrganizationNotifyWithUserSettingsJob;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService implements IUserService
{

    public function __construct(private IUserRepository $userRepository, private IGroupRepository $groupRepository)
    {
    }

    public function getUsersWithPagination(int|null $pageNumber): LengthAwarePaginator|null
    {
        return $this->userRepository->listAllPaginate();
    }

    public function storeUser(array $data): array
    {
        if (empty($data['signature_key'])){
            unset($data['signature_key']);
        }
        $this->checkPermissions($data['group_id']);
        $this->userRepository->create($data);

        return redirectWith([
            'route' => route('user.index'),
            'type' => 'success',
            'message' => trans('dashboard.created_success'),
        ]);
    }

    public function updateProfile(int $id, array $data): array
    {
        $this->checkPermissions($data['group_id']);
        $this->userRepository->update($data, ['id' => $id]);

        return redirectWith([
            'route' => route('user.index'),
            'type' => 'success',
            'message' => trans('dashboard.updated_success'),
        ]);
    }

    public function deleteUser(int $id): array
    {
        if (auth()->user()->id != $id) {
            $this->userRepository->delete(['id' => $id]);

            return redirectWith([
                'route' => route('user.index'),
                'type' => 'success',
                'message' => trans('dashboard.deleted_success'),
            ]);
        }
        throw new PermissionException(trans('dashboard.deleted_fail') . ',' . trans('dashboard.auth'));
    }

    public function savePaymentSettings(int $userId, array $paymentSettings)
    {
        DB::transaction(function () use ($userId, $paymentSettings) {
            $userObject = $this->userRepository->first(['id' => $userId]);
            $userObject->paymentTypes()->detach();
            $userObject->paymentMethod()->detach();
            $userObject->paymentTypes()->attach($paymentSettings['payment_type']);
            $userObject->paymentMethod()->attach($paymentSettings['methods']);

            $userObject->organization_supplier_id = $paymentSettings['supplier_settings'];
            $userObject->save();
            if (!empty($userObject->system_configuration) and optional($userObject?->system_configuration)['auth_type']){
                OrganizationNotifyWithUserSettingsJob::dispatch($userObject);
            }
        });
    }

    public function getUserData(int $id): array
    {
        $user = $this->userRepository->first(['id' => $id], ['supplierSettings']);
        $organization = $user->organization()->with('paymentTypes', 'suppliers', 'paymentMethod')->first();
        return ['user' => $user, 'organization' => $organization];
    }

    private function checkPermissions(int $groupId)
    {
        $group = $this->groupRepository->first(['id' => $groupId]);
        $userGroupLevel = auth()->user()->group;
        if ($userGroupLevel->level > $group->level) {
            throw new PermissionException();
        }
    }
}
