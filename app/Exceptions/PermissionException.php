<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class PermissionException extends Exception
{
    /**
     * @param  Throwable $e
     * @return Response
     */
    public function render(Throwable $e) : Response
    {
        return response()->view('errors.403', ['msg' => $e->getMessage()], 403);
    }
}
