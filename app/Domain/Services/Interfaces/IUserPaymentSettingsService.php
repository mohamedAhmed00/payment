<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface IUserPaymentSettingsService
{
    public function getUserPaymentSettingsForDashboard();

    public function getUserPaymentSettings(User|Authenticatable $user);
}
