<?php

declare(strict_types=1);

namespace App\Domain\DTO;

class TransactionDTO extends DataTransferObject
{

    public float $amount;

    public string $currency;

    public float $rate;

    public string $action;

    public string $services;

    public string $customer;

    public string $client_key;

    public int $payment_type_id;

    public ?int $payment_method_id;

    public int $user_id;

    public ?int $organization_id;

    public string $transaction_id;

    public string $invoice;

    public static function fromRequest(array $request): self
    {
        return new self([
            'amount' => $request['amount'],
            'currency' => $request['currency'],
            'rate' => $request['rate'],
            'action' => 'pay',
            'services' => json_encode($request['services']),
            'customer' => json_encode($request['customer']),
            'invoice' => json_encode(optional($request)['invoice']),
            'client_key' => $request['client_key'],
            'payment_type_id' => getPaymentTypes()->where('key',$request['payment_type'])->first()?->id,
            'payment_method_id' => getPaymentMethods()->where('key',optional($request)['payment_method'])->first()?->id,
            'user_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization?->id,
            'transaction_id' => md5( $request['client_key'] . auth()->user()->id . auth()->user()->organization?->id . microtime()),
        ]);
    }
}
