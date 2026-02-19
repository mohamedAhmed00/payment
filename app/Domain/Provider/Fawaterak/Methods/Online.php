<?php

namespace App\Domain\Provider\Fawaterak\Methods;

use App\Domain\Enum\ActionType;
use App\Domain\Provider\Contract\IPaymentMethod;
use App\Domain\Provider\Fawaterak\Enum\Statuses;
use App\Exceptions\ApiCustomException;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Throwable;


class Online implements IPaymentMethod
{
    /**
     * @param $transaction
     * @param $supplierSetting
     * @return array
     * @throws Throwable
     */
    public function pay($transaction, $supplierSetting): array
    {
        request()->validate([
            'items' => 'required_if:payment_method,online|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|numeric',
        ]);
        $this->validateTotalAmount();

        $supplierSetting = json_decode($supplierSetting, true);
        $status = getStatuses()->where('key', 'processing')->first();
        $transaction->statuses()->attach($status->id);
        $paymentData = $this->preparePayQueryParams($transaction, $supplierSetting);
        $signatureKey = makeDigitalSignature($paymentData, $transaction->user->signature_key);
        Cache::put($signatureKey, $paymentData, now()->addMinutes(20));

        return [
            'action' => [
                'content' => route('render_iframe',['signature_key' => $signatureKey, 'transaction_reference' => $transaction->transaction_id, 'supplier' => 'fawaterak']),
                'type' => ActionType::REDIRECT
            ],
            'type' => $transaction->paymentType->key,
            'status' => $status->key,
            'message' => __('Initiate payment request'),
            'transaction_id' => $transaction->transaction_id,
            'client_key' => $transaction->client_key
        ];
    }

    /**
     * @param Transaction $transaction
     * @param array $supplierSetting
     * @return array
     */
    private function preparePayQueryParams(Transaction $transaction, array $supplierSetting): array
    {
        return [
            'envType' => $supplierSetting['env_type'],
            'hashKey' => $this->generateHashKey($supplierSetting),
            'requestBody' => [
                'cartTotal' => $transaction->amount,
                'currency' => $transaction->currency,
                'customer' => $this->getCustomerData(json_decode($transaction->customer, true)),
                'redirectionUrls' => [
                    'successUrl' => route('returning', ['supplier' => 'fawaterak', 'status' => Statuses::SUCCESS, 'transaction_reference' => $transaction->transaction_id]),
                    'failUrl' => route('returning', ['supplier' => 'fawaterak', 'status' => Statuses::SUCCESS, 'transaction_reference' => $transaction->transaction_id]),
                    'pendingUrl' => route('returning', ['supplier' => 'fawaterak', 'status' => Statuses::SUCCESS, 'transaction_reference' => $transaction->transaction_id])
                ],
                'cartItems' => request()->all()['items']
            ],
            'plugin' => config('suppliers.fawaterak.'.$supplierSetting['env_type']. '.plugin')
        ];
    }

    private function generateHashKey($supplierSetting): string
    {
        $secretKey = $supplierSetting['supplier_api_key'];
        $queryParam = "Domain=" . config('app.url') . "&ProviderKey=" . $supplierSetting['supplier_provider_key'];
        return hash_hmac('sha256', $queryParam, $secretKey, false);
    }

    private function getCustomerData(array $customerDetails): array
    {
        return [
            'first_name' => $customerDetails['name'],
            'last_name' => $customerDetails['name'],
            'email' => $customerDetails['email'],
            'phone' => $customerDetails['phone'],
            'address' => $customerDetails['country'] . ',' . $customerDetails['city'] . ',' . $customerDetails['street1']
        ];
    }

    /**
     * @throws Throwable
     */
    private function validateTotalAmount(): void
    {
        $itemsTotalAmount = 0;
        foreach (request('items') as $item){
            $itemsTotalAmount += ($item['price'] * $item['quantity']);
        }
        throw_if(request('amount') !=  $itemsTotalAmount, new ApiCustomException(__('Order total amount dosen\'t match items total.')));
    }
}
