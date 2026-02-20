<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use App\Http\Requests\SubmitSurveyRequest;
use App\Http\Requests\SurveyFormRequest;
use App\Http\Resources\RecordResource;
use App\Http\Resources\ReportResource;
use App\Http\Resources\SurveyFormResource;
use App\Models\Questionnaire;
use App\Models\Record;
use App\Models\Report;
use App\Models\SurveyForm;
use Essa\APIToolKit\Api\ApiResponse;

class SurveyFormController extends Controller
{
    use ApiResponse;

    public function index(StatusRequest $request)
    {
        $status = $request->query("status");
        $pagination = $request->pagination;

        $survey_forms = SurveyForm::with([
            "user",
            "questionnaire",
            "questionnaire.sectionHeader",
            "questionnaire.answer",
            "chargings",
        ])
            ->when($status === "inactive", function ($query) {
                $query->onlyTrashed();
            })
            ->when($status && $status !== "inactive", function ($query) use (
                $status
            ) {
                $query->where("status", $status);
            })
            ->useFilters()
            ->dynamicPaginate();

        if ($survey_forms->isEmpty()) {
            return $this->responseSuccess(
                "No Survey Form Found",
                $survey_forms
            );
        }

        $survey_forms = SurveyFormResource::collection($survey_forms);

        return $this->responseSuccess(
            "Survey Forms retrieved successfully",
            $survey_forms
        );
    }

    public function store(SurveyFormRequest $request)
    {
        $validated = $request->validated();
        $questionnaire = Questionnaire::findOrFail(
            $validated["questionnaire_id"]
        );

        $attachmentPath = null;
        if ($request->hasFile("attachment")) {
            $attachmentPath = $request
                ->file("attachment")
                ->store("survey_attachments", "public");
        }

        $survey_form = SurveyForm::create([
            "user_id" => $validated["user_id"],
            "questionnaire_id" => $validated["questionnaire_id"],
            "name" => $validated["name"],
            "description" => $validated["description"] ?? null,
            "status" => $validated["status"] ?? "draft",
            "attachment" => $attachmentPath,
            "date_start" => $validated["date_start"] ?? null,
            "date_end" => $validated["date_end"] ?? null,
        ]);

        if (!empty($validated["charging_ids"])) {
            $survey_form->chargings()->attach($validated["charging_ids"]);
        }

        $survey_form->load([
           // "user",
            "questionnaire",
            "questionnaire.sectionHeader",
            "questionnaire.answer",
            "chargings",
        ]);

        return $this->responseCreated(
            "Survey Form created successfully",
            new SurveyFormResource($survey_form)
        );
    }

    public function update(SurveyFormRequest $request, $id)
    {
        $survey_forms = SurveyForm::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile("attachment")) {
            if (
                $survey_forms->attachment &&
                \Storage::disk("public")->exists($survey_forms->attachment)
            ) {
                \Storage::disk("public")->delete($survey_forms->attachment);
            }
            $validated["attachment"] = $request
                ->file("attachment")
                ->store("survey_attachments", "public");
        }

        $survey_forms->update($validated);

        if (!empty($validated["charging_ids"])) {
            $survey_forms->chargings()->sync($validated["charging_ids"]);
        }

        $survey_forms->load("chargings");

        return $this->responseSuccess(
            "Survey Form updated successfully",
            new SurveyFormResource($survey_forms)
        );
    }

    public function destroy($id)
    {
        $survey_forms = SurveyForm::withTrashed()->find($id);
        if (!$survey_forms) {
            return $this->responseNotFound(null, "Survey Form not found");
        }

        if ($survey_forms->trashed()) {
            $survey_forms->restore();
            $message = "Survey Form restored successfully";
        } else {
            $survey_forms->delete();
            $message = "Survey Form deleted successfully";
        }

        return $this->responseSuccess(
            $message,
            new SurveyFormResource($survey_forms)
        );
    }

    public function submitSurvey(SubmitSurveyRequest $request)
    {
        $validated = $request->validated();

        $survey_forms = SurveyForm::findOrFail($validated["survey_form_id"]);

        $records = new Record();
        $records->survey_form_id = $survey_forms->id;
        $records->save();

        $records->load([
            "surveyForm",
            "surveyForm.questionnaire",
            "surveyForm.questionnaire.sectionHeader",
            "surveyForm.questionnaire.answer",
            "surveyForm.chargings",
        ]);

        $reports = collect($validated["answers"])->map(function ($answer) use (
            $records
        ) {
            $reports = new Report();
            $reports->record_id = $records->id;
            $reports->questionnaire_id = $answer["questionnaire_id"]; //
            $reports->answer_value = $answer["answer_value"]; //
            $reports->save();
            return $reports;
        });

        return $this->responseCreated("Survey submitted successfully", [
            "records" => new RecordResource($records),
            "survey_forms" => new SurveyFormResource($survey_forms),
            "reports" => ReportResource::collection($reports),
        ]);
    }

    // public function submittedSurvey(StatusRequest $request)
    // {
    //     $status = $request->query("status");
    //     $pagination = $request->query("pagination");

    //     $survey_forms = SurveyForm::with([
    //         "user",
    //         "questionnaire",
    //         "questionnaire.sectionHeader",
    //         "questionnaire.answer",
    //         "chargings",
    //     ])
    //         ->when($status === "inactive", function ($query) {
    //             $query->onlyTrashed();
    //         })
    //         ->when($status && $status !== "inactive", function ($query) use (
    //             $status
    //         ) {
    //             $query->where("status", $status);
    //         })
    //         ->useFilters()
    //         ->dynamicPaginate();

    //     if ($survey_forms->isEmpty()) {
    //         return $this->responseSuccess(
    //             "No Survey Form Found",
    //             $survey_forms
    //         );
    //     }

    //    SurveyFormResource::collection($survey_forms)

    //     return $this->responseSuccess(
    //         "Survey Forms retrieved successfully",
    //         $survey_forms
    //     );
    // }
}