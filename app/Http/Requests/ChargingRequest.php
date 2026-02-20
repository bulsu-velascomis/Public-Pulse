<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "name" => "required|string|unique:chargings,name,".$this->route("charging"),
            "code" => "required|string|unique:chargings,code,".$this->route("charging"),
        ];
    }
}
