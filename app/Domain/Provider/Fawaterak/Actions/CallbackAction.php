<?php

namespace App\Domain\Provider\Fawaterak\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Exceptions\PaymentException;
use Symfony\Component\HttpFoundation\Response;

class CallbackAction implements IPaymentAction
{

    public function execute(...$data): array
    {
        [$data] = $data;
        $transactionRepository = resolve(ITransactionRepository::class);
        $transaction = $transactionRepository->first(['transaction_reference' => $data['invoice_id']]);
        if (empty($transaction)){
            throw new PaymentException(__('Not allowed callback'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        resolve(IPaymentLogRepository::class)->create([
            'request' => json_encode($data), 'supplier' => $data['supplier'],
            'user_id' => $transaction->user_id,
            'transaction_id' => $transaction->id
        ]);
        if ($data['invoice_status'] == 'paid'){
            $status = getStatuses()->where('key', 'paid')->first();
        } else {
            $status = getStatuses()->where('key', 'failed')->first();
        }
        $transaction->statuses()->attach($status->id);
        return ['transaction_id' => $transaction->id, 'transaction_key' => $transaction->client_key, 'status' => $status->key, 'user' => $transaction->user];
    }
}
