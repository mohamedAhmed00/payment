<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Repositories\Interfaces\IActivityLogRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) : mixed
    {
        $log['subject'] = trans('activity_logs.'.Str::replace('.', '_', $request->route()->getName()));
        $log['url'] = $request->fullUrl();
        $log['route_name'] = $request->route()->getName();
        $log['method'] = $request->method();
        $log['ip'] = $request->ip();
        $log['agent'] = $request->header('user-agent') ?? null;
        $log['user_id'] = auth()->check() ? auth()->user()?->id : null;
        $log['organization_id'] = auth()->check() ? auth()->user()?->organization_id : null;
        resolve(IActivityLogRepository::class)->create($log);

        return $next($request);
    }
}
