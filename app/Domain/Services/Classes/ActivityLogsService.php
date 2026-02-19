<?php

declare(strict_types=1);

namespace App\Domain\Services\Classes;

use App\Domain\Repositories\Interfaces\IActivityLogRepository;
use App\Domain\Services\Interfaces\IActivityLogsService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityLogsService implements IActivityLogsService
{
    public function __construct(private IActivityLogRepository $activityLogRepository)
    {
    }

    /**
     * @return LengthAwarePaginator|null
     */
    public function listLatestUsersActivities() : ?LengthAwarePaginator
    {
        if (auth()->check() && isset(auth()->user()->organization_id) && ! empty(auth()->user()->organization_id)) {
            $condition = ['organization_id' => auth()->user()->organization_id];
        } else {
            $condition = [];
        }

        return $this->activityLogRepository->listAllPaginate($condition);
    }
}
