<?php

namespace App\Http\Requests;

use App\Rules\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
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
            'organization' => 'required|array',
            'organization.name' => 'required|string',
            'organization.phone' => 'required|string',
            'organization.tax_number' => 'required|string',
            'organization.status' => ['required', Rule::in([0, 1])],
            'organization.address' => 'required|string',
            'organization.email' => 'required|email',
            'organization.logo' => 'sometimes|nullable|string',
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
            'supplier_settings.*.supplier_access_code' => 'nullable|string',
            'supplier_settings.*.supplier_merchant_identifier' => 'nullable|string',
            'supplier_settings.*.supplier_sha_request_phrase' => 'nullable|string',
            'supplier_settings.*.supplier_sha_response_phrase' => 'nullable|string',
            'supplier_settings.*.supplier_pay_integration_url' => 'nullable|string',
            'supplier_settings.*.supplier_refund_integration_url' => 'nullable|string',
            'supplier_settings.*.supplier_name' => 'nullable|string',
            'supplier_settings.*.supplier_currency' => 'string'
        ];
    }
}
