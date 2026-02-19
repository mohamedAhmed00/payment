<?php

declare(strict_types=1);

namespace App\Domain\Services\Interfaces;

use App\Domain\DTO\TransactionDTO;

interface IPaymentService
{
    public function pay(TransactionDTO $transaction);

    public function refund(array $data);

    public function returning(array $data);

    public function callback(array $data);

    public function renderIframe(array $data);
}
