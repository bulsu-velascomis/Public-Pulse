<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ChangePasswordRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ];
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         $user = $this->user();

    //         if (! Hash::check($this->current_password, $user->password)) {
    //             $validator->errors()->add('current_password', 'Current password is incorrect.');
    //         }
    //     });
    // }
}
