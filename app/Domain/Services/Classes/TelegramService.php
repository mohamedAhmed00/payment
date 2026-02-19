<?php

declare(strict_types=1);

namespace App\Domain\Services\Classes;

use App\Domain\Services\Interfaces\ITelegramService;
use Illuminate\Support\Facades\Http;

class TelegramService implements ITelegramService
{
    /**
     * @param  string $message
     * @return mixed
     */
    public function sendMessage(string $message) : mixed
    {
        return Http::asForm()->post(config('telegram.end_point').config('telegram.api_token').'/sendMessage', [
            'text' => $message,
            'chat_id' => config('telegram.chat_id'),
        ])->json();
    }

    /**
     * @return mixed
     */
    public function getOrganizationChannel() : mixed
    {
        return Http::asForm()->post(config('telegram.end_point').config('telegram.api_token').'/getUpdates')->json();
    }
}
