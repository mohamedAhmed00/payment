<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class PaymentType implements Rule
{
    private string $attribute;


    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;
        $online = getOnlineType();
        if (in_array($online->id, request('payment_type')))
        {
            return !empty($value);
        }
        return false;
    }

    public function message()
    {
        return __(sprintf('you must select %s', Str::replace('_', ' ',$this->attribute)));
    }
}
