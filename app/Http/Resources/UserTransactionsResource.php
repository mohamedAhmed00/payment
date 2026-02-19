<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTransactionsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'amount' => optional($this->resource)['amount'],
            'currency' => optional($this->resource)['currency'],
            'rate' => optional($this->resource)['rate'],
            'action' => optional($this->resource)['action'],
            'client_key' => optional($this->resource)['client_key'],
            'services' => json_decode(optional($this->resource)['services']),
            'customer' => json_decode(optional($this->resource)['customer']),
            'transaction_reference' => optional($this->resource)['transaction_reference'],
            'transaction_id' => optional($this->resource)['transaction_id'],
            'payment_type' => $this->resource?->paymentType?->key,
            'payment_method' => $this->resource?->paymentMethod?->key,
            'statuses' => TransactionStatusesResource::collection($this->resource?->statuses),
            'created_at' => optional($this->resource)['created_at'],
        ];
    }
}
