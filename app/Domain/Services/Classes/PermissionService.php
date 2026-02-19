<?php

declare(strict_types=1);

namespace App\Domain\Services\Classes;

use App\Domain\Repositories\Interfaces\IPermissionRepository;
use App\Domain\Repositories\Interfaces\IUserRepository;
use App\Domain\Services\Interfaces\IPermissionService;
use App\Models\Group;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use function is_array;

class PermissionService implements IPermissionService
{
    /**
     * PermissionService constructor.
     * @param IPermissionRepository $permissionRepository
     * @param IUserRepository       $userRepository
     */
    public function __construct(private IPermissionRepository $permissionRepository, private IUserRepository $userRepository)
    {
    }

    /**
     * @param  Group $group
     * @return array
     */
    public function getGroupPermissions(Group $group) : array
    {
        return [
            'group' => $group,
            'group_permissions' => array_column($group->permissions()->select('key')->get()->toArray(), 'key'),
            'permissions' => $this->permissionRepository->listAllBy([], [], ['key'])->toArray(),
        ];
    }

    /**
     * @return array
     */
    public function getAllPermissions() : array
    {
        Artisan::call('permission:seed');

        return redirectWith([
            'route' => redirect()->back()->getTargetUrl(),
            'type' => 'success',
            'message' => trans('dashboard.get_all_permissions'),
        ]);
    }

    /**
     * @param  Group $group
     * @param  array $data
     * @return array
     */
    public function updatePermissions(Group $group, array $data) : array
    {
        $group->permissions()->detach();
        foreach ($data['permissions'] as $permission) {
            $group->permissions()->attach($this->permissionRepository->first(['key' => $permission]));
        }
        Cache::forget('user_groups_'.$group->id);

        return redirectWith([
            'route' => route('permission.index', $group->id),
            'type' => 'success',
            'message' => trans('dashboard.updated_success'),
        ]);
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function checkPermission(string $key) : bool
    {
        if (! is_array($this->getUserPermission()) || false === array_search($key, Arr::pluck($this->getUserPermission(), 'key'))) {
            return false;
        }
        return true;
    }

    /**
     * @return array|null
     */
    private function getUserPermission() : array | null
    {
        $user = $this->userRepository->first(['id' => auth()->user()->id], ['group'], ['id', 'name', 'group_id']);

        return Cache::rememberForever('user_groups_'.$user->group_id, function () use ($user) {
            return $user->group->permissions->toArray();
        });
    }
}
