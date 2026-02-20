<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Http\Requests\QuestionnaireRequest;
use Illuminate\Foundation\Http\FormRequest;

class QuestionnaireRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "questions" => "required|array|min:1",
            "questions.*.section_header_id" =>
                "required|exists:section_headers,id",
            "questions.*.question_no" => "required|integer|min:1",
            "questions.*.question" => "required|string|max:500",
            "questions.*.type" =>
                "required|string|in:text,textarea,multiple choice,checkbox,optional",
            "questions.*.options" => "nullable|array",
            "questions.*.options.*" => "string",
        ];
    }
}
