<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Domain\Responder\Interfaces\IApiHttpResponder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PaymentException extends Exception
{

    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function render(): JsonResponse
    {
        return resolve(IApiHttpResponder::class)->response(message:$this->message, status: $this->code);
    }
}
