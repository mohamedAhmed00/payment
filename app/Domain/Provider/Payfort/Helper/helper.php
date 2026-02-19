<?php

use App\Domain\Provider\Payfort\Enum\Statuses;
use App\Domain\Repositories\Interfaces\ICurrencyRepository;
use App\Models\Status;

if (!function_exists('getCurrencyIsoCode')) {
    function getCurrencyIsoCode(string $currency)
    {
        $currency = resolve(ICurrencyRepository::class)->firstOrFail(conditions: ['currency_code' => $currency])->toArray();
        return config('currency.iso_code_factors')[$currency['iso_code']];
    }
}

if (!function_exists('checkResponseSignature')) {
    function checkResponseSignature(array $data, array $supplierSettings): bool
    {
        $signature = $data['signature'];
        unset($data['signature']);

        if (!empty($data['supplier'])) {
            unset($data['supplier']);
        }
        ksort($data);
        $shaResponsePhrase = $supplierSettings['supplier_sha_response_phrase'];
        return hash_equals(generateSignature($data, $shaResponsePhrase), $signature);
    }
}

if (!function_exists('generateSignature')) {
    function generateSignature($data, $supplierShaTypePhrase): string
    {
        ksort($data);
        $shaString = '';
        foreach ($data as $key => $value) {
            $shaString .= "$key=$value";
        }
        return hash('sha256', $supplierShaTypePhrase . $shaString . $supplierShaTypePhrase);
    }
}

if (!function_exists('getPayTransactionStatus')) {
    function getPayTransactionStatus($data, $payedAmount, $supplierSetting): Status
    {
        if (checkResponseSignature($data, json_decode($supplierSetting, true)) && checkPayedAmount($payedAmount, $data['amount'])) {
            $status = checkPayTransactionInCaseNotFraud($data);
        } else {
            $status = getStatuses()->where('key', 'fraud')->first();
        }
        return $status;
    }
}

if (!function_exists('checkPayTransactionInCaseNotFraud')) {
    function checkPayTransactionInCaseNotFraud($response): Status
    {
        if (in_array(substr($response['response_code'], 2), Statuses::SUCCESS)) {
            $status = in_array($response['status'], Statuses::HOLD) ?
                getStatuses()->where('key', 'pending')->first() :
                getStatuses()->where('key', 'paid')->first();
        } else {
            $status = getStatuses()->where('key', 'failed')->first();
        }
        return $status;
    }
}
