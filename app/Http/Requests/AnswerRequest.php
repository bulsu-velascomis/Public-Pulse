<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'answers' => 'required|array|min:1',
            'answers.*.question_no' => 'required|integer|min:1',
            'answers.*.answer' => 'required|string|max:1000',
        ];
    }
}
