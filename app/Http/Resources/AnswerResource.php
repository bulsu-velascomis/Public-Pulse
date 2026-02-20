<?php

namespace App\Http\Resources;

use App\Http\Resources\SpecifiedResource;
use App\Http\Resources\QuestionnaireResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    // public function toArray($request)
    // {
    //     return [
    //         "id" => $this->id,
    //         "options" => $this->options,
    //         "created_at" => $this->created_at,
    //     ];
    // }

    public function toArray($request)
    {

     $question = collect($this->questionnaire->questions)->firstWhere('question_no', $this->question_no);
        return [
            "id" => $this->id,
            "questionnaire_id" => $this->questionnaire_id,
            "questionnaire"=>new SpecifiedResource($this->whenLoaded("questionnaire")),
            "question"=>$question,
            "question_no" => $this->question_no,
            "answer" => $this->answer,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
