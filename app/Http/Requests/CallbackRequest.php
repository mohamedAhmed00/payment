<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CallbackRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'supplier' => ['required', 'exists:suppliers,key'],
            'tran_ref' => 'required_if:supplier,paytab|string',
            'invoice_id' =>'required_if:supplier,fawaterk',
            'fort_id' => 'required_if:supplier,payfort|string',
            'merchant_reference' => 'required_if:supplier,payfort',
            'response_code' => 'required_if:supplier,payfort',
            'invoice_status' => 'required_if:supplier,fawaterk',
        ];
    }
}
