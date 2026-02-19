<?php

declare(strict_types=1);

namespace App\Domain\Services\Classes;

use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Domain\Services\Interfaces\ITransactionService;

class TransactionService implements ITransactionService
{

    public function __construct(private readonly ITransactionRepository $transactionRepository)
    {
    }

    public function getTransactions($clientKey)
    {
        return $this->transactionRepository->listAllBy(['client_key' => $clientKey, 'user_id' => auth()->user()->id],['statuses', 'paymentType', 'paymentMethod']);
    }
}
