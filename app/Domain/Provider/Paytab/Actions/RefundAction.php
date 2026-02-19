<?php

namespace App\Domain\Provider\Paytab\Actions;

use App\Domain\Provider\Contract\IPaymentAction;
use App\Domain\Provider\Paytab\Enum\Statuses;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Exceptions\PaymentException;
use Illuminate\Support\Facades\Http;
use Throwable;

class RefundAction implements IPaymentAction
{

    public function execute(...$data)
    {
        [$refundedTransaction, $refundTransaction , $supplierSetting, $data] = $data;
        $settings = json_decode($supplierSetting);
        try {
            $request = [
                "profile_id" => $settings->supplier_profile_id,
                "tran_type" => "refund",
                "tran_class" => "ecom",
                "cart_id" => json_decode($refundedTransaction->services)->id,
                "cart_currency" => $refundedTransaction->currency,
                "cart_amount" => $refundedTransaction->amount,
                "cart_description" => $data['reason'],
                "tran_ref" => $refundTransaction->transaction_reference
            ];
            $response = Http::withHeaders(['Content-Type' => 'application/json', 'Authorization' => $settings->supplier_server_key])
                ->post($settings->supplier_pay_integration_url . 'new/invoice', $request);
            resolve(IPaymentLogRepository::class)->create([
                'request' => json_encode($request), 'supplier' => $settings->name,
                'response' => json_encode($response->json()), 'user_id' => auth()->user()->id,
                'transaction_id' => $refundedTransaction->id
            ]);
            if (!$response->successful()){
                throw new PaymentException(json_encode($response->json()), $response->status());
            }
            $response = $response->json();
            $refundedTransaction->transaction_reference = $response['tran_ref'];
            $refundedTransaction->save();
            if (in_array(optional($response['payment_result'])['response_status'], Statuses::SUCCESS)){
                $status = getStatuses()->where('key', 'refunded')->first();
            } else {
                $status = getStatuses()->where('key', 'failed')->first();
            }
            $refundedTransaction->statuses()->attach($status->id);
            return [
                'payment_type' => $refundedTransaction->paymentType->key,
                'status' => $status->key,
                'message' => optional(optional($response)['payment_result'])['response_message'],
                'client_key' => $refundedTransaction->client_key,
                'transaction_id' => $refundedTransaction->transaction_id,
            ];
        } catch(Throwable $exception){
            throw new PaymentException($exception->getMessage(), $exception->getCode());
        }
    }
}
