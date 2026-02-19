<?php

declare(strict_types=1);

namespace App\Domain\Responder\Interfaces;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;

interface IHttpResponder
{
    /**
     * @param  string          $view
     * @param  array           $data
     * @return Renderable|View
     */
    public function response(string $view, array $data = []) : Renderable | View;
}
