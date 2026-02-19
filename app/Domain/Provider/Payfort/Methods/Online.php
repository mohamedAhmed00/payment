<?php

namespace App\Domain\Provider\Payfort\Methods;

use App\Domain\Provider\Contract\IPaymentMethod;
use App\Models\Transaction;


class Online implements IPaymentMethod
{
    public const PAY_COMMAND = 'PURCHASE';

    /**
     * @param $transaction
     * @param $supplierSetting
     * @return array
     */
    public function pay($transaction, $supplierSetting): array
    {
        $supplierSetting = json_decode($supplierSetting, true);
        $status = getStatuses()->where('key', 'processing')->first();
        $transaction->statuses()->attach($status->id);

        return [
            'action' => [
                'content' => $this->getForm($this->preparePayQueryParams($transaction, $supplierSetting), $supplierSetting),
                'type' => 'form'
            ],
            'type' => $transaction->paymentType->key,
            'status' => $status->key,
            'message' => __('Initiate payment request'),
            'transaction_id' => $transaction->transaction_id,
            'client_key' => $transaction->client_key
        ];
    }

    /**
     * @param $requestParams
     * @param $supplierSettings
     * @return string
     */
    private function getForm($requestParams, $supplierSettings): string
    {
        $redirectUrl = $supplierSettings['supplier_pay_integration_url'];
        $form = "<html xmlns='https://www.w3.org/1999/xhtml'><head></head><body>";
        $form .= "<form id='frm' action='$redirectUrl' method='post' name='frm'>";
        foreach ($requestParams as $a => $b) {
            $form .= "<input type='hidden' name='" . htmlentities($a) . "' value='" . htmlentities($b) . "'>";
        }
        $form .= "<script type='text/javascript'>";
        $form .= "document.frm.submit();";
        $form .= "</script>";
        $form .= "</form></body></html>";
        return $form;
    }

    /**
     * @param Transaction $transaction
     * @param array $supplierSetting
     * @return array
     */
    private function preparePayQueryParams(Transaction $transaction, array $supplierSetting): array
    {
        $requestParams = [
            'command' => self::PAY_COMMAND,
            'access_code' => $supplierSetting['supplier_access_code'],
            'merchant_identifier' => $supplierSetting['supplier_merchant_identifier'],
            'merchant_reference' => $transaction->transaction_id,
            'amount' => $transaction->amount * getCurrencyIsoCode($transaction->currency),
            'currency' => $transaction->currency,
            'language' => 'en',
            'customer_email' => $transaction->user->email,
            'return_url' => route('returning', ['supplier' => 'payfort']),
        ];
        $requestParams['signature'] = generateSignature($requestParams, $supplierSetting['supplier_sha_request_phrase']);
        return $requestParams;
    }
}
