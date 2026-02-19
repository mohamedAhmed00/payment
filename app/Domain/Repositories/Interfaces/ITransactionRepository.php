<?php

namespace App\Domain\Repositories\Interfaces;

use App\Models\Transaction;

interface ITransactionRepository
{
    public function getTransactionSumGroupedByAction(Transaction $transaction);
}
