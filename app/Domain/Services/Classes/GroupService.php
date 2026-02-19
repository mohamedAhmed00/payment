<?php

declare(strict_types=1);

namespace App\Domain\Services\Classes;

use App\Domain\Repositories\Interfaces\IGroupRepository;
use App\Domain\Services\Interfaces\IAbilityToDelete;
use App\Domain\Services\Interfaces\IGroupService;
use App\Exceptions\PermissionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupService implements IGroupService, IAbilityToDelete
{
    /**
     * GroupService constructor.
     * @param IGroupRepository $groupRepository
     */
    public function __construct(private IGroupRepository $groupRepository)
    {
    }

    /**
     * @return LengthAwarePaginator|null
     */
    public function getGroupsWithPagination() : LengthAwarePaginator | null
    {
        return $this->groupRepository->listAllPaginate();
    }

    /**
     * @return array|Collection
     */
    public function getAllGroups() : Collection | array
    {
        return  $this->groupRepository->getAllGroupEqualToOrLowerLevel();
    }

    /**
     * @param  array $data
     * @return array
     */
    public function storeUserGroup(array $data) : array
    {
        $userGroupLevel = auth()->user()->group->level;
        if (null !== $userGroupLevel and $userGroupLevel > $data['level']) {
            $data['level'] = $userGroupLevel;
        }
        $this->groupRepository->create($data);

        return redirectWith([
            'route' => route('group.index'),
            'type' => 'success',
            'message' => trans('dashboard.created_success'),
        ]);
    }

    /**
     * @param  int                $id
     * @return Builder|Model|null
     */
    public function getUserGroup(int $id) : Builder | Model | null
    {
        return $this->groupRepository->first(['id' => $id], ['users']);
    }

    /**
     * @param  int   $id
     * @param  array $data
     * @return array
     */
    public function updateUserGroup(int $id, array $data) : array
    {
        $userGroupLevel = auth()->user()->group->level;
        if (null !== $userGroupLevel and $userGroupLevel > $data['level']) {
            unset($data['level']);
        }

        $this->groupRepository->update($data, ['id' => $id]);

        return redirectWith([
            'route' => route('group.index'),
            'type' => 'success',
            'message' => trans('dashboard.updated_success'),
        ]);
    }

    /**
     * @param  int                 $id
     * @throws PermissionException
     * @return array
     */
    public function deleteGroup(int $id) : array
    {
        $group = $this->groupRepository->first(['id' => $id], ['users'], ['id']);
        if (! $this->checkUserCanDelete($group)) {
            return redirectWith([
                'route' => route('group.index'),
                'type' => 'error',
                'message' => trans('errors.cant_delete_group'),
            ]);
        }
        $this->groupRepository->delete(['id' => $id]);

        return redirectWith([
            'route' => route('group.index'),
            'type' => 'success',
            'message' => trans('dashboard.deleted_success'),
        ]);
    }

    /**
     * @param  Model               $group
     * @throws PermissionException
     * @return mixed
     */
    public function checkUserCanDelete(Model $group) : mixed
    {
        $userGroupLevel = auth()->user()->group->level;
        if (null !== $userGroupLevel and $userGroupLevel <= $group->level) {
            throw new PermissionException();
        }

        return $group->users->isEmpty();
    }
}
