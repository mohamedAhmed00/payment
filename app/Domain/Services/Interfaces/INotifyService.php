<?php

namespace App\Domain\Services\Interfaces;

use App\Exceptions\PaymentException;
use App\Models\User;
use Illuminate\Database\Query\Builder;

interface INotifyService
{
    /**
     * @param User|Builder $user
     * @return void
     * @throws PaymentException
     */
    public function notifyOrganizationWithUserSettings(User|Builder $user): void;

    public function notifyUserWithTransactionUpdates($payload): void;
}
