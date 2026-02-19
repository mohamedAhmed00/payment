<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules() : array
    {
        $id = $this->route()->parameter('user') ? ','.$this->route()->parameter('user') : '';
        $confirmedPassword = $this->route()->parameter('user') ? 'nullable' : 'nullable|confirmed';

        return [
            'name' => 'required|string',
            'signature_key' => 'nullable|string',
            'returning_url' => 'nullable|string',
            'email' => 'required|unique:users,email'.$id,
            'new_password' => $confirmedPassword.'|min:8|confirmed',
            'group_id' => 'required|exists:groups,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'auth_type' => ['nullable', Rule::in(config('app.callback_system_authentication_types'))],
            'login_url' =>  new RequiredIf( in_array($this->auth_type, config('app.callback_system_authentication_types'))),
            'notification_url' =>  new RequiredIf( in_array($this->auth_type, config('app.callback_system_authentication_types'))),
            'username' => 'required_if:auth_type,token',
            'password' => 'required_if:auth_type,token',
            'origin' => 'required_if:auth_type,token',
            'agent' => 'required_if:auth_type,backOffice',
        ];
    }
}
