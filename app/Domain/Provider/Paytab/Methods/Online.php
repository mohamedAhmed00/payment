<?php

namespace App\Domain\Provider\Paytab\Methods;

use App\Domain\Enum\ActionType;
use App\Domain\Provider\Contract\IPaymentMethod;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Exceptions\PaymentException;
use Illuminate\Support\Facades\Http;
use Throwable;

class Online implements IPaymentMethod
{
    public function pay($transaction, $supplierSetting)
    {
        $settings = json_decode($supplierSetting);
        $request = $this->prepareRequest($settings, $transaction);
        $header = ['Content-Type' => 'application/json', 'Authorization' => $settings->supplier_server_key];
        $status = getStatuses()->where('key', 'processing')->first();
        $transaction->statuses()->attach($status->id);
        try {
            $response = Http::withHeaders($header)->post($settings->supplier_pay_integration_url . 'request', $request);
            resolve(IPaymentLogRepository::class)->create([
                'request' => json_encode($request), 'supplier' => $settings->name,
                'response' => json_encode($response->json()), 'user_id' => auth()->user()->id,
                'transaction_id' => $transaction->id
            ]);
            if (!$response->successful()){
                throw new PaymentException(json_encode($response->json()), $response->status());
            }
            $response = $response->json();
            $transaction->transaction_reference = $response['tran_ref'];
            $transaction->save();
            return [
                'action' => [
                    'content' => $response['redirect_url'],
                    'type' => ActionType::REDIRECT
                ],
                'type' => $transaction->paymentType->key,
                'status' => $status->key,
                'message' => __('Initiate payment request'),
                'transaction_id' => $transaction->transaction_id,
                'client_key' => $transaction->client_key,
            ];
        } catch(Throwable $exception){
            throw new PaymentException($exception->getMessage(), $exception->getCode());
        }
    }

    private function prepareRequest($settings, $transaction){
        return [
            "profile_id" => $settings->supplier_profile_id,
            "tran_type" => "sale",
            "tran_class" => "ecom",
            "cart_currency" => $transaction->currency,
            "cart_amount" => $transaction->amount,
            "cart_id" => json_decode($transaction->services)->id,
            "cart_description" => json_decode($transaction->services)->description,
            "paypage_lang" => "en",
            "customer_details" => json_decode($transaction->customer, true),
            "callback" => route('callback', ['supplier' => 'paytab']),
            "return" => route('returning', ['supplier' => 'paytab']),
            "hide_shipping" => true
        ];
    }
}
