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

class OrganizationNotifyWithTransactionUpdatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $payload)
    {
    }

    public function handle(): void
    {
        resolve(INotifyService::class)->notifyUserWithTransactionUpdates($this->payload);
    }
}
