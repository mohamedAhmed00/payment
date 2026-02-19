<?php

use App\Domain\Repositories\Interfaces\ICurrencyRepository;
use App\Domain\Repositories\Interfaces\IPaymentMethodRepository;
use App\Domain\Repositories\Interfaces\IPaymentTypeRepository;
use App\Domain\Repositories\Interfaces\IStatusRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

if (! function_exists('getOnlineType')) {
    function getOnlineType()
    {
        return Cache::remember('online_type', config('cache.ttl'), function () {
            return resolve(IPaymentTypeRepository::class)->first(['key' => 'online']);
        });
    }
}

function getModels() : array
{
    $models = [];
    $ignore = ['.', '..'];
    foreach (array_filter(scandir(app_path().'/Models'), fn ($file) => ! in_array($file, $ignore)) as $model) {
        $models[] = basename($model, '.php');
    }

    return $models;
}

function redirectWith(array $data) : array
{
    return [
        'redirect' => optional($data)['route'],
        'redirect_data' => ['status' => [
            'type' => optional($data)['type'],
            'message' => optional($data)['message'],
        ]],
    ];
}

if (! function_exists('getPaymentTypes')) {
    function getPaymentTypes()
    {
        return Cache::rememberForever('payment_types', function () {
            return resolve(IPaymentTypeRepository::class)->listAllBy();
        });
    }
}

if (! function_exists('getPaymentMethods')) {
    function getPaymentMethods()
    {
        return Cache::rememberForever('payment_methods', function () {
            return resolve(IPaymentMethodRepository::class)->listAllBy();
        });
    }
}

if (! function_exists('getCurrenciesCode')) {
    function getCurrenciesCode()
    {
        return Cache::rememberForever('currencies_code', function () {
            return Arr::flatten(resolve(ICurrencyRepository::class)->listAllBy(select: ['currency_code'])->toArray());
        });
    }
}

if (! function_exists('getCurrencies')) {
    function getCurrencies()
    {
        return Cache::rememberForever('currencies', function () {
            return resolve(ICurrencyRepository::class)->listAllBy(select:['currency_code', 'iso_code'])->toArray();
        });
    }
}

if (! function_exists('makeDigitalSignature')) {
    function makeDigitalSignature($data, $signatureKey)
    {
        return hash('sha256', prepareDataBeforeMakeSignature($data, $signatureKey));
    }
}

if (! function_exists('prepareDataBeforeMakeSignature')) {
    function prepareDataBeforeMakeSignature($data, $signatureKey)
    {
        ksort($data);
        $hash = '';
        foreach ($data as $key => $item){
            if (is_array($item)){
                foreach ($item as $key1 => $singleItem)
                {
                    if (is_array($singleItem)){
                        $hash .= $key1 . json_encode($singleItem);

                    } else{
                        $hash .= $key1 . $singleItem;
                    }
                }
                continue;
            }
            $hash .= $key . $item;
        }
        $hash .= $signatureKey;
        return Str::replace(' ', '' , $hash);
    }
}

if (! function_exists('getStatuses')) {
    function getStatuses()
    {
        return Cache::rememberForever('statuses', function () {
            return resolve(IStatusRepository::class)->listAllBy();
        });
    }
}

if (! function_exists('getJsonFileContentAsArray')) {
    function getJsonFileContentAsArray(string $path): array
    {
        return json_decode(file_get_contents(storage_path($path) ), true);
    }
}

if (!function_exists('checkPayedAmount')){
    /**
     * @param $amountBeforePay
     * @param $amountAfterPay
     * @return bool
     */
    function checkPayedAmount($amountBeforePay, $amountAfterPay): bool
    {
        return (string)$amountAfterPay === (string)$amountBeforePay;
    }
}
