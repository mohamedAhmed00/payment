<?php

namespace App\Domain\Rules;

use Closure;
use Illuminate\Support\Str;

abstract class Validator
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $this->applyRules($builder);
    }

    protected abstract function applyRules($builder);

}
