<?php

namespace App\Domain\Provider\Payfort\Methods;

use App\Domain\Provider\Contract\IPaymentMethod;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Exceptions\PaymentException;
use App\Models\Transaction;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Facades\Http;
use Throwable;

class Invoice implements IPaymentMethod
{
    const DEFAULT_NOTIFICATION_TYPE = 'EMAIL';
    const PAYMENT_TYPE = 'PURCHASE';
    const PAY_COMMAND = 'PAYMENT_LINK';

    /**
     * @param $transaction
     * @param $supplierSetting
     * @return array
     * @throws PaymentException
     */
    public function pay($transaction, $supplierSetting): array
    {
        $supplierSetting = json_decode($supplierSetting, true);
        $status = getStatuses()->where('key', 'processing')->first();
        $transaction->statuses()->attach($status->id);
        $requestData = $this->preparePayQueryParams($transaction, $supplierSetting);

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($supplierSetting['supplier_refund_integration_url'], $requestData);

            resolve(IPaymentLogRepository::class)->create([
                'request' => json_encode($requestData), 'supplier' => $supplierSetting['name'],
                'response' => json_encode($response->json()), 'user_id' => auth()->user()->id,
                'transaction_id' => $transaction->id
            ]);

            throw_if(!$response->successful(), new PaymentException(json_encode($response), $response->status()));

            throw_if(!checkResponseSignature($response->json(), $supplierSetting), __('Transaction process is falsified'));

            return [
                'action' => [],
                'type' => $transaction->paymentType->key,
                'status' => $status->key,
                'message' => __('Initiate payment request'),
                'transaction_id' => $transaction->transaction_id,
                'client_key' => $transaction->client_key
            ];
        } catch (Throwable $exception) {
            throw new PaymentException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param Transaction $transaction
     * @param array $supplierSetting
     * @return array
     */
    private function preparePayQueryParams(Transaction $transaction, array $supplierSetting): array
    {
        $requestParams = [
            'service_command' => self::PAY_COMMAND,
            'access_code' => $supplierSetting['supplier_access_code'],
            'merchant_identifier' => $supplierSetting['supplier_merchant_identifier'],
            'merchant_reference' => $transaction->transaction_id,
            'amount' => $transaction->amount * getCurrencyIsoCode($transaction->currency),
            'currency' => $transaction->currency,
            'language' => 'en',
            'customer_email' => json_decode($transaction->customer, true)['email'],
            'request_expiry_date' => (new DateTime('+7 days'))->format(DateTimeInterface::W3C),
            'notification_type' => self::DEFAULT_NOTIFICATION_TYPE,
            'link_command' => self::PAYMENT_TYPE
        ];
        $requestParams['signature'] = generateSignature($requestParams, $supplierSetting['supplier_sha_request_phrase']);
        return $requestParams;
    }
}
