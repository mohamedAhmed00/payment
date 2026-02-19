<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

interface ITelegramService
{
    public function sendMessage(string $message);

    public function getOrganizationChannel();
}
