<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'reason' => 'nullable|string',
            'transaction_id' => 'required|exists:transactions,transaction_id',
        ];
    }
}
