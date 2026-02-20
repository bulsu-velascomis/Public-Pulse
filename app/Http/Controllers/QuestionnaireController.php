<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Requests\DisplayRequest;
use App\Http\Requests\QuestionnaireRequest;
use App\Http\Resources\QuestionnaireResource;

class QuestionnaireController extends Controller
{
    use ApiResponse;

    public function index(DisplayRequest $request)
    {
        $status = $request->query("status");
        $pagination = $request->query("pagination");

        $questionnaire = Questionnaire::with("sectionHeader")->when($status === "inactive", function (
            $query
        ) {
            $query->onlyTrashed();
        })
            ->useFilters()
            ->dynamicPaginate();

        if ($questionnaire->isEmpty()) {
            return $this->responseNotFound(null, "No questionnaire Found");
        }

        if (!$pagination) {
            $questionnaire = QuestionnaireResource::collection($questionnaire);
        } else {
            $questionnaire = QuestionnaireResource::collection($questionnaire);
        }
        
        return $this->responseSuccess(
            "Questionnaires retrieved successfully",
            $questionnaire
        );
    }

    public function store(QuestionnaireRequest $request)
    {
        $validated = $request->validated();

        $questionnaire = Questionnaire::create([
            "section_header_id" =>
                $validated["questions"][0]["section_header_id"],
            "questions" => $validated["questions"],

            "type" => $validated["type"] ?? "text",
        ]);
        $questionnaire->load("sectionHeader");

        return $this->responseCreated(
            "Questionnaire created successfully",
            new QuestionnaireResource($questionnaire)
        );
    }

    public function update(QuestionnaireRequest $request, $id)
    {
        $questionnaire = Questionnaire::find($id);

        if (!$questionnaire) {
            return $this->responseNotFound(null, "Questionnaire not found");
        }

        if (isset($request->validated()["questions"][0]["section_header_id"])) {
            $questionnaire->section_header_id = $request->validated()[
                "questions"
            ][0]["section_header_id"];
        }
        $questionnaire->update($request->validated());

        $questionnaire->load("sectionHeader");

        return $this->responseSuccess(
            "Questionnaire updated successfully",
            new QuestionnaireResource($questionnaire)
        );
    }

    public function destroy($id)
    {
        $questionnaire = Questionnaire::withTrashed()->find($id);

        if (!$questionnaire) {
            return $this->responseNotFound(null, "Questionnaire not found");
        }

        if ($questionnaire->trashed()) {
            $questionnaire->restore();
            return $this->responseSuccess(
                "Questionnaire restored successfully",
                new QuestionnaireResource($questionnaire)
            );
        } else {
            $questionnaire->delete();
            return $this->responseSuccess(
                "Questionnaire deleted successfully",
                new QuestionnaireResource($questionnaire)
            );
        }
    }
}

