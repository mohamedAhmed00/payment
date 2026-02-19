<?php

namespace App\Domain\Factory;

use App\Domain\Notification\BackOffice;
use App\Domain\Notification\INotification;
use App\Domain\Notification\Token;
use App\Exceptions\ApiCustomException;
use UnhandledMatchError;

class NotificationFactory implements INotificationFactory
{
    public function getSystemNotificationObject(string $type): INotification
    {
        try {
            return match ($type) {
                'token' => new Token(),
                'backOffice' => new BackOffice()
            };
        } catch (UnhandledMatchError $exception){
            throw new ApiCustomException('Not exist notification type');
        }
    }
}
