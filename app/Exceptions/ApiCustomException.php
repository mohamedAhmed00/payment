<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiCustomException extends Exception
{

    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function render(): JsonResponse
    {
        return response()->json(
            [
                'message' => $this->message,
                'errors' => [$this->message],
            ], $this->code);
    }
}
