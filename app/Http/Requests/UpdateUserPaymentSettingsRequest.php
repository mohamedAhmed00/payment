<?php

namespace App\Http\Requests;

use App\Rules\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPaymentSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payment_type'  => 'required|array',
            'payment_type.*' => 'required|numeric|exists:payment_types,id',
            'suppliers' => [new PaymentType()],
            'suppliers.*' => [new PaymentType(), 'numeric'],
            'methods' => [new PaymentType()],
            'methods.*' => [new PaymentType(), 'numeric'],
            'supplier_settings' => [new PaymentType()],
            'supplier_settings.*.supplier_id' => [new PaymentType(), 'numeric'],
            'supplier_settings.*.name' => [new PaymentType(), 'string'],
            'supplier_settings.*.supplier_server_key' => 'nullable|string',
            'supplier_settings.*.supplier_profile_id' => 'nullable|string',
            'supplier_settings.*.supplier_name' => 'nullable|string',
        ];
    }
}
