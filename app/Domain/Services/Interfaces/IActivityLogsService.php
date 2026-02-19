<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IActivityLogsService
{
    /**
     * @return LengthAwarePaginator|null
     */
    public function listLatestUsersActivities() : ?LengthAwarePaginator;
}
