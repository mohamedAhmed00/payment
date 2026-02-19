<?php

namespace App\Domain\Repositories\Classes;

use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends AbstractRepository implements ITransactionRepository
{

    public function getTransactionSumGroupedByAction(Transaction $transaction)
    {
        return $this->model->select(DB::raw('SUM(amount) as amount'), 'action')
            ->where('client_key', $transaction->client_key)
            ->where('payment_type_id', $transaction->payment_type_id)
            ->groupBy('action')->get();
    }
}
