<?php

namespace App\Domain\PaymentTypes;

use App\Domain\DTO\TransactionDTO;
use App\Models\Transaction;

interface IPaymentType
{
    public function pay(TransactionDTO $transaction);

    public function refund(Transaction $transaction, array $data);
}
