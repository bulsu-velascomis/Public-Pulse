<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use App\Http\Requests\RecordRequest;
use App\Http\Resources\RecordResource;
use App\Models\Record;
use App\Services\Record\RecordService;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    use ApiResponse;

    protected RecordService $recordService;

    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
    }

    public function index(StatusRequest $request)
    {
        $status = $request->query("status");

        $record = $this->recordService->getRecords($status);

        if ($record->isEmpty()) {
            return $this->responseSuccess("No Record Found", $record);
        }

        $record = RecordResource::collection($record);  

        return $this->responseSuccess(
            "Records retrieved successfully",
            $record
        );
    }

    public function store(RecordRequest $request)
    {
        $record = $this->recordService->createRecord($request->validated());

        $record = Record::with('surveyForms')->find($record->id);

        return $this->responseCreated(
            "Record created successfully",
            new RecordResource($record)
        );
    }

    public function update(Request $request, int $id)
    {
        $record = $this->recordService->updateRecord($id, $request->validated());

        if (! $record) {
            return $this->responseNotFound("Record not found", $record);
        }

        $record->update($request->validated());
        $record = Record::with('surveyForms')->find($record->id);

        return $this->responseSuccess(
            "Record updated successfully",
            new RecordResource($record)
        );
    }

    // public function destroy(int $id)
    // {
    //     $record = $this->recordService->deleteRecord($id);

    //     $record = Record::withTrashed()->find($id);

    //     if (!$record) {
    //         return $this->responseNotFound("Record not found", $record);
    //     }

    //     $is_active = Record::withTrashed()
    //         ->where("id", $id)
    //         ->first();

    //     if ($record->trashed()) {
    //         $record->restore();
    //         $message = "Record restored successfully";
    //     } else {
    //         $record->delete();
    //         $message = "Record deleted successfully";
    //     }

    //     return $this->responseSuccess($message, new RecordResource($record));
    // }
}
