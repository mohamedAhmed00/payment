<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IGroupService
{
    /**
     * @return LengthAwarePaginator|null
     */
    public function getGroupsWithPagination() : LengthAwarePaginator | null;

    /**
     * @return array|Collection
     */
    public function getAllGroups() : Collection | array;

    /**
     * @param  array $group_data
     * @return array
     */
    public function storeUserGroup(array $group_data) : array;

    /**
     * @param  int                    $id
     * @throws AuthorizationException
     * @return Builder|Model|null
     */
    public function getUserGroup(int $id) : Builder | Model | null;

    /**
     * @param  int   $id
     * @param  array $group_data
     * @return array
     */
    public function updateUserGroup(int $id, array $group_data) : array;

    /**
     * @param  int   $id
     * @return array
     */
    public function deleteGroup(int $id) : array;
}
