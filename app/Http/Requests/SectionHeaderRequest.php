<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SectionHeaderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['string', 'max:255', Rule::unique('section_headers', 'name')->ignore($this->id)],
        ];
    }

}
