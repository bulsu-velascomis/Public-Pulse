<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "user_id" => "required|exists:users,id",
            "questionnaire_id" => "required|exists:questionnaires,id",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "status" => "nullable|in:active,inactive,draft,archived",
            "attachment" => "nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx",
            "date_start" => "nullable|date|after_or_equal:now",
            "date_end" => "nullable|date|after:date_start",

            "charging_ids" => "nullable|array",
            "charging_ids.*" => "exists:chargings,id",
        ];
    }
}
