<?php

declare(strict_types=1);

namespace App\Domain\Responder\Interfaces;

use Illuminate\Http\JsonResponse;

interface IApiHttpResponder
{
    public function response(array $data = [], string|null $message = null, int $status = 200): JsonResponse;
}
