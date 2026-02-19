<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Responder\Interfaces\IHttpRedirectResponder;
use App\Domain\Services\Interfaces\IActivityLogsService;
use App\Models\ActivityLog;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;

class ActivityLogController extends Controller
{

    public function __construct(private IActivityLogsService $activityLogsService, private IHttpRedirectResponder $httpResponder)
    {
    }

    public function index() : View|Renderable
    {
        $this->authorize('viewAny', ActivityLog::class);

        return  $this->httpResponder->response(
            'dashboard.logs.index',
            ['logs' => $this->activityLogsService->listLatestUsersActivities()]
        );
    }
}
