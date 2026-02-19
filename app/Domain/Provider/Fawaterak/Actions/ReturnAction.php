<?php

namespace App\Domain\Provider\Fawaterak\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Fawaterak\Enum\Statuses;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Exceptions\PaymentException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response;

class ReturnAction implements IPaymentAction
{
    /**
     * @throws PaymentException
     */
    public function execute(...$data): Redirector|Application|RedirectResponse
    {
        [$data] = $data;
        $transactionRepository = resolve(ITransactionRepository::class);
        $transaction = $transactionRepository->first(['transaction_id' => $data['transaction_reference']]);
        if (empty($transaction)) {
            throw new PaymentException(__('Not allowed returning'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        resolve(IPaymentLogRepository::class)->create([
            'request' => json_encode($data), 'supplier' => $data['supplier'],
            'user_id' => $transaction->user_id,
            'transaction_id' => $transaction->id
        ]);
        $status = $data['status'] == Statuses::SUCCESS ?
            getStatuses()->where('key', 'paid')->first() :
            getStatuses()->where('key', 'failed')->first();
        $transaction->update(['transaction_reference' => $data['invoice_id']]);
        $transaction->statuses()->attach($status->id);

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
