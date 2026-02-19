<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'payment_type' => optional($this->resource)['payment_type'],
            'status' => optional($this->resource)['status'],
            'message' => optional($this->resource)['message'],
            'client_key' => optional($this->resource)['client_key'],
            'transaction_id' => optional($this->resource)['transaction_id'],
            'signature' => $this->resource['signature']
        ];
    }
}
