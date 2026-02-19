<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Rules\CheckIFrameDataInCache;
use Illuminate\Foundation\Http\FormRequest;

class RenderIFrameRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }


    public function rules() : array
    {
        return [
            'supplier' => ['required', 'exists:suppliers,key'],
            'transaction_reference' =>'required',
            'signature_key' => ['required', new CheckIFrameDataInCache()]
        ];
    }
}
