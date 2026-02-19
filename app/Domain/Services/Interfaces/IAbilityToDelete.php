<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IAbilityToDelete
{
    /**
     * @param  Model $model
     * @return mixed
     */
    public function checkUserCanDelete(Model $model) : mixed;
}
