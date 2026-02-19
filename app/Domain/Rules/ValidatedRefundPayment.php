<?php

namespace App\Domain\Rules;

use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Exceptions\ApiCustomException;

class ValidatedRefundPayment extends  Validator
{
    protected function applyRules($builder){
        $transactionRepository = resolve(ITransactionRepository::class);
        $transaction = $transactionRepository->first(conditions:['transaction_id' => request('transaction_id')]);
        $totalAmount = 0;
        $transactions = $transactionRepository->getTransactionSumGroupedByAction($transaction)->toArray();
        foreach ($transactions as  $transaction){
            $totalAmount += ($transaction['action'] == 'pay')? $transaction['amount'] : ( $transaction['amount'] * -1 );
        }
        if (request('amount') > $totalAmount){
            throw new ApiCustomException(__('Refund amount must be less or Equal to the paid amount'));
        }
        return $builder;
    }

}
