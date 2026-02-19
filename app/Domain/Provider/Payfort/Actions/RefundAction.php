<?php

namespace App\Domain\Provider\Payfort\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Payfort\Enum\Statuses;
use App\Exceptions\PaymentException;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Throwable;

class RefundAction implements IPaymentAction
{
    public function execute(...$data)
    {
        [$refundedTransaction, $refundTransaction, $supplierSetting, $data] = $data;
        $settings = json_decode($supplierSetting, true);
        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($settings['supplier_refund_integration_url'], $this->prepareRefundQueryParams($refundTransaction, $settings, $data));
            $refundedTransaction->transaction_reference = $response['fort_id'];
            $refundedTransaction->save();
            if (in_array(substr($response['response_code'], 2),Statuses::SUCCESS)) {
                $status = getStatuses()->where('key', 'refunded')->first();
            } else {
                $status = getStatuses()->where('key', 'failed')->first();
            }
            $refundedTransaction->statuses()->attach($status->id);

            return [
                'payment_type' => $refundedTransaction->paymentType->key,
                'status' => $status->key,
                'message' => optional($response)['response_message'],
                'client_key' => $refundedTransaction->client_key,
                'transaction_id' => $refundedTransaction->transaction_id,
            ];
        } catch (Throwable $exception) {
            throw new PaymentException($exception->getMessage(), $exception->getCode());
        }
    }

    private function prepareRefundQueryParams(Transaction $refundTransaction, array $settings, array $data): array
    {
        $requestParams = [
            'command' => Statuses::REFUND_COMMAND,
            'access_code' => $settings['supplier_access_code'],
            'merchant_identifier' => $settings['supplier_merchant_identifier'],
            'merchant_reference' => $refundTransaction->transaction_id,
            'amount' => $data['amount'] * getCurrencyIsoCode($refundTransaction->currency),
            'currency' => $refundTransaction->currency,
            'language' => 'en',
        ];
        if (!is_null($refundTransaction->transaction_reference)){
            $requestParams['fort_id'] = $refundTransaction->transaction_reference;
        }
        $requestParams['signature'] = generateSignature($requestParams, $settings['supplier_sha_request_phrase']);
        return $requestParams;
    }
}
