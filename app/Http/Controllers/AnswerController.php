<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Questionnaire;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerRequest;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Requests\DisplayRequest;
use App\Http\Resources\AnswerResource;

class AnswerController extends Controller
{
    use ApiResponse;

    public function index(DisplayRequest $request)
    {
        $status = $request->query("status");
        $pagination = $request->query("pagination");

        $answers = Answer::when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })
            ->with(["questionnaire"])
            ->useFilters()
            ->dynamicPaginate();

        if (!$pagination) {
            $answers = AnswerResource::collection($answers);
        } else {
            $answers = AnswerResource::collection($answers);
        }

        return $this->responseSuccess(
            "Answers retrieved successfully",
            $answers
        );
    }

    public function store(AnswerRequest $request)
    {
        $validated = $request->validated();

        $questionnaire = Questionnaire::find($validated["questionnaire_id"]);

        if (!$questionnaire) {
            return $this->responseNotFound(null, "Questionnaire not found");
        }

        $validquestionNum = array_column(
            $questionnaire->questions,
            "question_no"
        );

        $savedAnswers = [];

        foreach ($validated["answers"] as $answerData) {
            if (!in_array($answerData["question_no"], $validquestionNum)) {
                return $this->responseUnprocessable(
                    "Invalid question number: " . $answerData["question_no"]
                );
            }

            $answer = Answer::updateOrCreate(
                [
                    "questionnaire_id" => $validated["questionnaire_id"],
                    "question_no" => $answerData["question_no"],
                ],
                [
                    "answer" => $answerData["answer"],
                ]
            );

            $answer->load(["questionnaire"]);
            $savedAnswers[] = $answer;
        }

        return $this->responseCreated(
            "Answers saved successfully",
            AnswerResource::collection(collect($savedAnswers))
        );
    }
}

// class AnswerController extends Controller
// {
//     use ApiResponse;

//     public function index(DisplayRequest $request)
//     {
//         $status = $request->query("status");
//         $pagination = $request->pagination;
//         $answer = Answer::when($status === "inactive", function ($query) {
//             $query->onlyTrashed();
//         })
//             ->useFilters()
//             ->dynamicPaginate();

//         if ($answer->isEmpty()) {
//             return $this->responseSuccess("No Answer Found", $answer);
//         }

//         if (!$pagination) {
//             AnswerResource::collection($answer);
//         } else {
//             $answer = AnswerResource::collection($answer);
//         }

//         return $this->responseSuccess(
//             "Answers retrieved successfully",
//             $answer
//         );
//     }

//     public function store(AnswerRequest $request)
//     {
//         $data = $request->validated();
//         $answers = [];

//         foreach ($data["options"] as $answerData) {
//             if (
//                 empty($answerData["option_text"]) &&
//                 empty($answerData["option_value"])
//             ) {
//                 continue;
//             }

//             $answer = Answer::create([
//                 "options" => [
//                     "option_text" => $answerData["option_text"] ?? null,
//                     "option_value" => $answerData["option_value"] ?? null,
//                 ],
//             ]);
//             $answers[] = new AnswerResource($answer);
//         }

//         return $this->responseSuccess("Answers created successfully", $answers);
//     }
// }
