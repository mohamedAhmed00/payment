<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AuthToken implements Rule
{

    public function passes($attribute, $value)
    {
        return Auth::attempt(request()->only('credentials')['credentials']);
    }

    public function message()
    {
        return __('Invalid credentials');
    }
}
