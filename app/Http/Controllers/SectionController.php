<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SectionHeader;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Requests\DisplayRequest;
use App\Http\Resources\SectionResource;
use App\Services\Section\SectionService;
use App\Http\Controllers\SectionController;
use App\Http\Requests\SectionHeaderRequest;

class SectionController extends Controller
{
    use ApiResponse;

    protected SectionService $sectionService;

    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    public function index(DisplayRequest $request)
    {
        $status = $request->query("status");

        $section = $this->sectionService->getSections($status);

        if ($section->isEmpty()) {
            return $this->responseSuccess("No Section Found", $section);
        }

        SectionResource::collection($section);

        return $this->responseSuccess(
            "Sections retrieved successfully",
            $section
        );
    }

    public function store(SectionHeaderRequest $request)
    {
        $section = $this->sectionService->createSection($request->validated());

        return $this->responseCreated(
            "Section created successfully",
            new SectionResource($section)
        );
    }

    public function update(SectionHeaderRequest $request, $id)
    {
        $section = $this->sectionService->updateSection(
            $id,
            $request->validated()
        );

        if (!$section) {
            return $this->responseNotFound("Section not found", $section);
        }

        $section->update($request->validated());

        return $this->responseSuccess(
            "Section updated successfully",
            new SectionResource($section)
        );
    }

    public function destroy($id)
    {
        $section = $this->sectionService->deleteOrRestoreSection($id);

        $section = SectionHeader::withTrashed()->find($id);

        if (!$section) {
            return $this->responseNotFound("Section not found", $section);
        }

        $is_active = SectionHeader::withTrashed()
            ->where("id", $id)
            ->first();

        if ($section->trashed()) {
            $section->restore();
            $message = "Section restored successfully";
        } else {
            $section->delete();
            $message = "Section deleted successfully";
        }

        return $this->responseSuccess($message, $section);
    }
}
