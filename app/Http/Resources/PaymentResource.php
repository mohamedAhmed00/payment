<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'action' => [
                'content' => optional(optional($this->resource)['action'])['content'],
                'type' => optional(optional($this->resource)['action'])['type'],
            ],
            'type' => optional($this->resource)['type'],
            'status' => optional($this->resource)['status'],
            'client_key' => optional($this->resource)['client_key'],
            'transaction_id' => optional($this->resource)['transaction_id'],
            'message' => optional($this->resource)['message'],
            'signature' => $this->resource['signature']
        ];
    }
}
