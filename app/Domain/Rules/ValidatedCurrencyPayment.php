<?php

namespace App\Domain\Rules;

use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Exceptions\ApiCustomException;

class ValidatedCurrencyPayment extends  Validator
{
    protected function applyRules($builder){
        $transaction = resolve(ITransactionRepository::class)->first(['client_key' => request('client_key')]);
        if (!empty($transaction)){
            if ($transaction->currency != request('currency')){
                throw new ApiCustomException(__('Payment amount must be the same currency that paid before'));
            }
        }
        return $builder;
    }

}
