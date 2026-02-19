<?php

namespace App\Domain\Provider\Payfort\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Payfort\Enum\Statuses;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Domain\Repositories\Interfaces\ITransactionRepository;
use App\Exceptions\PaymentException;
use App\Models\Status;
use Symfony\Component\HttpFoundation\Response;

class CallbackAction implements IPaymentAction
{
    public function execute(...$data)
    {
        [$data] = $data;
        $transactionRepository = resolve(ITransactionRepository::class);
        $transaction = $transactionRepository->first(['transaction_id' => $data['merchant_reference']]);
        resolve(IPaymentLogRepository::class)->create(['request' => json_encode($data), 'supplier' => $data['supplier'], 'transaction_id' => $transaction->id]);
        if (empty($transaction)){
            throw new PaymentException(__('Not allowed callback'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $status = $this->getCallBackTransactionStatus($data, $transaction->user->supplierSettings->settings);
        $transaction->statuses()->attach($status->id);
        $transaction->update(['transaction_reference' => $data['fort_id']]);
        return ['transaction_id' => $transaction->transaction_id ,'transaction_key' => $transaction->client_key, 'status' => $status->key, 'user' => $transaction->user];
    }

    private function getCallBackTransactionStatus(array $data, $supplierSetting): Status
    {
        if (checkResponseSignature($data, json_decode($supplierSetting, true))) {
            $status = in_array(substr($data['response_code'], 2), Statuses::SUCCESS) ?
                getStatuses()->where('key', 'paid')->first() :
                getStatuses()->where('key', 'failed')->first();
        } else {
            $status = getStatuses()->where('key', 'fraud')->first();
        }
        return $status;
    }
}
