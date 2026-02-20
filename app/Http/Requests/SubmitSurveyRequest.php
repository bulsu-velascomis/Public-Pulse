<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitSurveyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "survey_form_id" => "required|exists:survey_forms,id",
            "user_id" => "required|exists:users,id",
            "answers" => "required|array|min:1",
            "answers.*.questionnaire_id" => "required|exists:questionnaires,id",
            "answers.*.answer_value" => "required|string",
        ];
    }
}
