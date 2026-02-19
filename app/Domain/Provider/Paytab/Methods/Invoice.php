<?php

namespace App\Domain\Provider\Paytab\Methods;

use App\Domain\Provider\Contract\IPaymentMethod;
use App\Domain\Repositories\Interfaces\IPaymentLogRepository;
use App\Domain\Rules\ValidatedInvoicePayment;
use App\Exceptions\PaymentException;
use App\Mail\SendInvoiceMailLink;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Invoice implements IPaymentMethod
{
    use ValidatesRequests;

    public function pay($transaction, $supplierSetting)
    {
        request()->validate([
            'invoice' => 'required_if:payment_method,invoice|array',
            'invoice.shipping_charges' => 'sometimes|numeric',
            'invoice.extra_charges' => 'sometimes|numeric',
            'invoice.total' => 'required|numeric',
            'invoice.activation_date' => 'sometimes|string',
            'invoice.expiry_date' => 'sometimes|string',
            'invoice.due_date' => 'sometimes|string',
            'invoice.line_items' => 'required_if:payment_method,invoice|array',
            'invoice.line_items.*.sku' => 'sometimes|string',
            'invoice.line_items.*.description' => 'sometimes|string',
            'invoice.line_items.*.url' => 'sometimes|string',
            'invoice.line_items.*.net_total' => 'required|numeric',
            'invoice.line_items.*.discount_rate' => 'required|numeric',
            'invoice.line_items.*.discount_amount' => 'required|numeric',
            'invoice.line_items.*.tax_rate' => 'required|numeric',
            'invoice.line_items.*.tax_total' => 'required|numeric',
            'invoice.line_items.*.total' => 'required|numeric',
            'invoice.line_items.*.unit_cost' => 'required_if:payment_method,invoice|numeric',
            'invoice.line_items.*.quantity' => 'required_if:payment_method,invoice|integer'
        ]);
        app(Pipeline::class)
            ->send(auth()->user())
            ->through([ValidatedInvoicePayment::class])
            ->thenReturn()
            ->get();

        $settings = json_decode($supplierSetting);
        $request = $this->prepareRequest($settings, $transaction);
        $header = ['Content-Type' => 'application/json', 'Authorization' => $settings->supplier_server_key];
        $status = getStatuses()->where('key', 'processing')->first();
        $transaction->statuses()->attach($status->id);
        try {
            $response = Http::withHeaders($header)->post($settings->supplier_pay_integration_url . 'new/invoice', $request);
            resolve(IPaymentLogRepository::class)->create([
                'request' => json_encode($request), 'supplier' => $settings->name,
                'response' => json_encode($response->json()), 'user_id' => auth()->user()->id,
                'transaction_id' => $transaction->id
            ]);
            if (!$response->successful()){
                throw new PaymentException(json_encode($response->json()), $response->status());
            }
            $response = $response->json();
            $transaction->transaction_reference = md5($transaction->id);
            $transaction->save();
            Mail::to($request['customer_details']['email'])->send(new SendInvoiceMailLink($response['invoice_link'], auth()->user()?->organization));
            return [
                'action' => [
                    'content' => '',
                    'type' => 'mail'
                ],
                'type' => $transaction->paymentType->key,
                'status' => $status->key,
                'message' => __('Invoice link sent to customer email'),
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
            "return" => route('returning', ['supplier' => 'paytab', 'invoice_ref' => md5($transaction->id)]),
            "hide_shipping" => true,
            "invoice" => json_decode($transaction->invoice, true),
        ];
    }
}
