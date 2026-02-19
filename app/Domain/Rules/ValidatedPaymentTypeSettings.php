<?php

namespace App\Domain\Rules;


use App\Exceptions\ApiCustomException;

class ValidatedPaymentTypeSettings extends  Validator
{
    protected function applyRules($builder){
        if (!in_array(request('payment_type'), $builder->paymentTypes->pluck('key')->toArray())){
            throw new ApiCustomException(__('Payment type not configured for this user'));
        }
        return $builder;
    }

}
