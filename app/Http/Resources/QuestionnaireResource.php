<?php

namespace App\Http\Resources;

use App\Models\Answer;
use App\Http\Resources\SectionResource;
use App\Http\Resources\AnswerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionnaireResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "section_header_id" => $this->section_header_id,
            "type" => $this->type,

            "questions" => $this->questions,

            "section_header" => new SectionResource(
                $this->whenLoaded("sectionHeader")
            ),
        ];
    }
}


 // $questionsData = $this->questions;
        // if (is_array($questionsData) && isset($questionsData["answer_id"])) {
        //     $answerIds = is_array($questionsData["answer_id"])
        //         ? $questionsData["answer_id"]
        //         : [$questionsData["answer_id"]];

        //     $answers = Answer::whereIn("id", $answerIds)->get();
        //     $questionsData["answers"] = $answers
        //         ->map(function ($answer) {
        //             return [
        //                 "id" => $answer->id,
        //                 "options" => $answer->options,
        //             ];
        //         })
        //         ->toArray();
        // }

        // return [
        //     "id" => $this->id,
        //     "questions" => $questionsData,
        //     "display_order" => $this->display_order,
        //     "created_at" => $this->created_at,
        //     "updated_at" => $this->updated_at,
        //     "deleted_at" => $this->deleted_at,

        //     "section_header" => new SectionResource(
        //         $this->whenLoaded("sectionHeader")
        //     ),
        //      "answer" => new AnswerResource(
        //         $this->whenLoaded("answer")
        //     ),
        // ];