<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

interface ITransactionService
{
    public function getTransactions($clientKey);
}
