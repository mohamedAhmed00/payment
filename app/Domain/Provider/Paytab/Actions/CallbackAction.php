<?php

namespace App\Domain\Provider\Paytab\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Paytab\Enum\Statuses;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Exceptions\PaymentException;
use Symfony\Component\HttpFoundation\Response;

class CallbackAction implements IPaymentAction
{

    public function execute(...$data)
    {
        [$data] = $data;
        $transactionRepository = resolve(ITransactionRepository::class);
        $transaction = $transactionRepository->first(['transaction_reference' => $data['tran_ref']]);
        if (empty($transaction)){
            throw new PaymentException(__('Not allowed callback'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        resolve(IPaymentLogRepository::class)->create([
            'request' => json_encode($data), 'supplier' => $data['supplier'],
            'user_id' => $transaction->user_id,
            'transaction_id' => $transaction->id
        ]);
        if (in_array($data['payment_result']['response_status'], Statuses::SUCCESS)){
            $status = getStatuses()->where('key', 'paid')->first();
        } else {
            $status = getStatuses()->where('key', 'failed')->first();
        }
        $transaction->statuses()->attach($status->id);
        return ['transaction_id' => $transaction->id, 'transaction_key' => $transaction->client_key, 'status' => $status->key, 'user' => $transaction->user];
    }
}
