<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Classes;

use App\Domain\Repositories\Interfaces\IGroupRepository;

class GroupRepository extends AbstractRepository implements IGroupRepository
{
    public function getAllGroupEqualToOrLowerLevel()
    {
        return $this->model->where('level', '>=', auth()->user()->group->level)->get();
    }
}
