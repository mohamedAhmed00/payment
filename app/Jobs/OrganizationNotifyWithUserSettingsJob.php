<?php

namespace App\Jobs;

use App\Domain\Services\Interfaces\INotifyService;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrganizationNotifyWithUserSettingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param User|Builder $user
     */
    public function __construct(public User|Builder $user)
    {
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        resolve(INotifyService::class)->notifyOrganizationWithUserSettings($this->user);
    }
}
