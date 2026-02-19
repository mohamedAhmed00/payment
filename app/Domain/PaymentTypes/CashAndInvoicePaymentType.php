<?php

namespace App\Domain\PaymentTypes;

use App\Domain\DTO\TransactionDTO;
use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Models\Transaction;

class CashAndInvoicePaymentType implements IPaymentType
{
    public function pay(TransactionDTO $transaction)
    {
        $transactionRepository = resolve(ITransactionRepository::class);
        $status = getStatuses()->where('key', 'paid')->first();
        $transaction = $transactionRepository->create((array)$transaction);
        $transaction->statuses()->attach($status->id);
        return [
            'type' => $transaction->paymentType->key,
            'status' => $status->key,
            'client_key' => $transaction->client_key,
            'message' => __('Paid Successfully'),
            'transaction_id' => $transaction->transaction_id,
            'action' => [
                'content' => null,
                'type' => null
            ],
        ];
    }

    public function refund(Transaction $transaction, array $data) {
        $transactionRepository = resolve(ITransactionRepository::class);
        $status = getStatuses()->where('key', 'refunded')->first();
        $transaction = $transactionRepository->create([
            'currency' => $transaction->currency,
            'rate' => $transaction->rate,
            'services' => $transaction->services,
            'customer' => $transaction->customer,
            'client_key' => $transaction->client_key,
            'payment_type_id' => $transaction->payment_type_id,
            'user_id' => $transaction->user_id,
            'organization_id' => $transaction->organization_id,
            'amount' => $data['amount'],
            'action' => 'refund',
            'transaction_id' => md5( $transaction->client_key . $transaction->user_id . $transaction->organization_id . now())
        ]);
        $transaction->statuses()->attach($status->id);
        return [
            'payment_type' => $transaction->paymentType->key,
            'status' => $status->key,
            'message' => __('Refunded Successfully'),
            'client_key' => $transaction->client_key,
            'transaction_id' => $transaction->transaction_id,
        ];
    }
}
