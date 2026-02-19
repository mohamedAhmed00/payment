<?php

declare(strict_types=1);

namespace App\Domain\Responder\Interfaces;

use Illuminate\Http\RedirectResponse;

interface IHttpRedirectResponder extends IHttpResponder
{
    /**
     * @param $data
     * @return RedirectResponse
     */
    public function redirect($data) : RedirectResponse;
}
