<?php

namespace App\Domain\Factory;

use App\Domain\Notification\INotification;

interface INotificationFactory
{
    public function getSystemNotificationObject(string $type) : INotification;
}
