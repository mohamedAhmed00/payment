<?php

namespace App\Domain\Rules;


use App\Exceptions\ApiCustomException;

class ValidatedOnlinePayment extends  Validator
{
    protected function applyRules($builder){
        if (request('payment_type') == 'online'){
            if (empty($builder->supplierSettings)){
                throw new ApiCustomException(__('No supplier assigned to this user'));
            }

            if (request('currency') != json_decode($builder->supplierSettings->settings)->supplier_currency){
                throw new ApiCustomException(__('Not allowed currency'));
            }

            if (!in_array(request('payment_method'), $builder->paymentMethod?->pluck('key')->toArray())){
                throw new ApiCustomException(__('Payment method not configured for this user'));
            }
        }
        return $builder;
    }

}
