<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Signature implements Rule
{

    public function passes($attribute, $value)
    {
        return hash_equals(hash('sha256', prepareDataBeforeMakeSignature(request()->except('signature'), auth()->user()->signature_key)), request()->get('signature'));
    }

    public function message()
    {
        return __('Request body not correct');
    }
}
