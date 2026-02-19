<?php

namespace App\Domain\PaymentTypes;

use App\Domain\DTO\TransactionDTO;
use App\Domain\Factory\IPaymentSupplierFactory;
use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Models\Transaction;

class OnlinePaymentType implements IPaymentType
{
    public function pay(TransactionDTO $transaction)
    {
        $supplierSetting = auth()->user()->supplierSettings->settings;
        $transactionRepository = resolve(ITransactionRepository::class);
        $status = getStatuses()->where('key', 'initial')->first();
        $transaction = $transactionRepository->create((array)$transaction);
        $transaction->statuses()->attach($status->id);
        $supplier = resolve(IPaymentSupplierFactory::class)->getSupplierObject(json_decode($supplierSetting)->name);
        return $supplier->pay()->execute($transaction, $supplierSetting);
    }

    public function refund(Transaction $refundTransaction, array $data) {
        $supplierSetting = auth()->user()->supplierSettings->settings;
        $transactionRepository = resolve(ITransactionRepository::class);
        $status = getStatuses()->where('key', 'initial')->first();
        $refundedTransaction = $transactionRepository->create([
            'currency' => $refundTransaction->currency,
            'rate' => $refundTransaction->rate,
            'services' => $refundTransaction->services,
            'customer' => $refundTransaction->customer,
            'client_key' => $refundTransaction->client_key,
            'payment_type_id' => $refundTransaction->payment_type_id,
            'user_id' => $refundTransaction->user_id,
            'organization_id' => $refundTransaction->organization_id,
            'amount' => $data['amount'],
            'action' => 'refund',
            'transaction_id' => md5( $refundTransaction->client_key . $refundTransaction->user_id . $refundTransaction->organization_id . now())
        ]);
        $refundedTransaction->statuses()->attach($status->id);
        $supplier = resolve(IPaymentSupplierFactory::class)->getSupplierObject(json_decode($supplierSetting)->name);
        return $supplier->refund()->execute($refundedTransaction, $refundTransaction , $supplierSetting, $data);
    }
}
