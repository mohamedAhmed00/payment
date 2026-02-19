<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\Currency;
use App\Rules\Signature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'currency' => ['required', 'string', new Currency()],
            'rate' => 'required|numeric',
            'payment_type' => ['required', Rule::in(getPaymentTypes()->pluck('key'))],
            'payment_method' => ['required_if:payment_type,online', Rule::in(getPaymentMethods()->pluck('key'))],
            'services' => 'required|array',
            'services.id' => 'required',
            'services.description' => 'required',
            'customer' => 'required|array',
            'customer.name' => 'required|string',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string',
            'customer.street1' => 'required|string',
            'customer.city' => 'required|string',
            'customer.state' => 'required|string',
            'customer.country' => 'required|string',
            'client_key' => 'required|string',
            'signature' => ['required', 'string', new Signature()]
        ];
    }
}
