<?php

namespace App\Domain\Services\Classes;

use App\Domain\Factory\INotificationFactory;
use App\Domain\Services\Interfaces\INotifyService;
use App\Domain\Services\Interfaces\IUserPaymentSettingsService;
use App\Exceptions\PaymentException;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Http;
use Throwable;

class NotifyService implements INotifyService
{
    /**
     * @throws PaymentException
     */
    public function notifyOrganizationWithUserSettings(User|Builder $user): void
    {
        try {
            $notification = resolve(INotificationFactory::class)->getSystemNotificationObject($user->system_configuration['auth_type']);
            $notification->setUser($user);
            Http::withHeaders($notification->buildHeader())
                ->post($notification->getNotificationUrl() ,
                    resolve(IUserPaymentSettingsService::class)->getUserPaymentSettings($user)
                );
        } catch (Throwable $exception) {
            throw new PaymentException($exception->getMessage());
        }
    }

    public function notifyUserWithTransactionUpdates($payload): void
    {
        try {
            $notification = resolve(INotificationFactory::class)->getSystemNotificationObject($payload['user']->system_configuration['auth_type']);
            $notification->setUser($payload['user']);
            $user = $payload['user'];
            unset($payload['user']);
            $payload['signature'] = makeDigitalSignature($payload, $user->signature_key);
            Http::withHeaders($notification->buildHeader())
                ->post($notification->getNotificationUrl() , $payload);
        } catch (Throwable $exception) {
            throw new PaymentException($exception->getMessage());
        }
    }
}
