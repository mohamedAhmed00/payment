<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Currency implements Rule
{

    public function passes($attribute, $value)
    {
        return in_array($value, getCurrenciesCode());
    }

    public function message()
    {
        return __('not allowed Currency');
    }
}
