<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Essa\APIToolKit\Api\ApiResponse;

class ReportController extends Controller
{
    use ApiResponse;

    public function index(StatusRequest $request)
    {
        $status = $request->query("status");
        $pagination = $request->pagination;
        $report = Report::with([
            "record",
            "record.surveyForm",
            "record.surveyForm.user",
            "record.surveyForm.questionnaire",
            "record.surveyForm.questionnaire.sectionHeader",
            "record.surveyForm.questionnaire.answer",
            "record.surveyForm.chargings",
        ])
            ->when($status === "inactive", function ($query) {
                $query->onlyTrashed();
            })
            ->useFilters()
            ->dynamicPaginate();

        if ($report->isEmpty()) {
            return $this->responseSuccess("No Report Found", $report);
        }

        if (!$pagination) {
            ReportResource::collection($report);
        } else {
            $report = ReportResource::collection($report);
        }

        return $this->responseSuccess("Report retrieved successfully", $report);
    }

    // public function store(ReportRequest $request)
    // {
    //     $validated = $request->validated();

    //     $report = Report::create([
    //         'record_id' => $validated['record_id'],
    //     ]);

    //     return $this->responseCreated(
    //         'Report created successfully',
    //         new ReportResource($report)
    //     );
    // }

    // public function update(ReportRequest $request, int $id)
    // {
    //     $report = Report::withTrashed()->find($id);

    //     if (! $report) {
    //         return $this->responseNotFound('Report not found', $report);
    //     }

    //     $report->update($request->validated());

    //     return $this->responseSuccess(
    //         'Report updated successfully',
    //         new ReportResource($report)
    //     );
    // }

    // public function destroy(int $id)
    // {
    //     $report = Report::withTrashed()->find($id);

    //     if (! $report) {
    //         return $this->responseNotFound('Report not found', $report);
    //     }

    //     if ($report->trashed()) {
    //         $report->restore();

    //         return $this->responseSuccess(
    //             'Report restored successfully',
    //             new ReportResource($report)
    //         );
    //     }

    //     $report->delete();

    //     return $this->responseSuccess(
    //         'Report deleted successfully',
    //         null
    //     );
    // }
}
