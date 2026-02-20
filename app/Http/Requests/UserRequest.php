<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'lastname' => 'required|string',
            'suffix' => 'nullable|string',
            'username' => 'required|unique:users,username,'.$this->route('user'),
            'password' => 'nullable|string|min:6',
            'role_id'=>'nullable|exists:roles,id', 
            'charging_id'=>'required|exists:chargings,id',
        ];
    }
}
