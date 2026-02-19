<?php

namespace App\Domain\Provider\Payfort\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Exceptions\PaymentException;
use Symfony\Component\HttpFoundation\Response;

class ReturnAction implements IPaymentAction
{
    public function execute(...$data)
    {
        [$data] = $data;
        $transactionRepository = resolve(ITransactionRepository::class);
        $transaction = $transactionRepository->first(['transaction_id' => $data['merchant_reference']]);
        if (empty($transaction)){
            throw new PaymentException(__('Not allowed returning'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        resolve(IPaymentLogRepository::class)->create(['request' => json_encode($data), 'supplier' => $data['supplier'], 'user_id' => $transaction->user_id, 'transaction_id' => $transaction->id]);
        $status = getPayTransactionStatus($data, $transaction->amount * getCurrencyIsoCode($transaction->currency), $transaction->user->supplierSettings->settings);
        $transaction->statuses()->attach($status->id);
        $transaction->update(['transaction_reference' => $data['fort_id']]);
        $signatureData = [
            'status' => $status->key,
            'client_key' => $transaction->client_key,
            'transaction_id' => $transaction->transaction_id
        ];
        return redirect($transaction->user->returning_url .
            '?status=' . $signatureData['status'] .
            '&client_key=' . $signatureData['client_key'] .
            '&transaction_id=' . $signatureData['transaction_id'] .
            '&signature=' . makeDigitalSignature($signatureData, $transaction->user->signature_key)
        );
    }
}
