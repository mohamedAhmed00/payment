<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionStatusesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->resource?->name,
            'key' => $this->resource?->key,
            'created_at' => $this->resource?->created_at,
        ];
    }
}
