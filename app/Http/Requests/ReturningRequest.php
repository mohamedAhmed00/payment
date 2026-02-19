<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ReturningRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'supplier' => ['required', 'exists:suppliers,key'],
            'tranRef' => 'required_if:supplier,paytab|string',
            'status' => 'required_if:supplier,fawaterk',
            'invoice_id' =>'required_if:supplier,fawaterk',
            'transaction_reference' =>'required_if:supplier,fawaterk',
            'fort_id' => 'required_if:supplier,payfort|string',
            'merchant_reference' => 'required_if:supplier,payfort',
            'respStatus' => 'required_if:supplier,paytab',
            'response_code' => 'required_if:supplier,payfort'
        ];
    }
}
