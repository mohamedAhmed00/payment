<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPaymentSettingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'key' => optional($this->resource)['key'],
            'name' => optional($this->resource)['name'],
            $this->mergeWhen(!empty(optional($this->resource)['currency']), function () {
                return [
                    'currency' => optional($this->resource)['currency'],
                ];
            }),
            $this->mergeWhen(!empty(optional($this->resource)['payment_methods']), function () {
                return [
                    'payment_methods' => optional($this->resource)['payment_methods']
                ];
            }),
        ];
    }
}
