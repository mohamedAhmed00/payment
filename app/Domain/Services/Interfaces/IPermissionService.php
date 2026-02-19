<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

use App\Models\Group;

interface IPermissionService
{
    /**
     * @param  Group $group
     * @return mixed
     */
    public function getGroupPermissions(Group $group) : mixed;

    /**
     * @return mixed
     */
    public function getAllPermissions() : mixed;

    /**
     * @param  Group $group
     * @param  array $data
     * @return array
     */
    public function updatePermissions(Group $group, array $data) : array;

    /**
     * @param  string $key
     * @return bool
     */
    public function checkPermission(string $key) : bool;
}
