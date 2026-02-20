<?php

namespace App\Http\Controllers;

use App\Models\Charging;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Requests\DisplayRequest;
use App\Http\Requests\ChargingRequest;
use App\Http\Resources\ChargingResource;

class ChargingController extends Controller
{
    use ApiResponse;

    public function index(DisplayRequest $request)
    {
        $status = $request->query("status");
        $pagination = $request->pagination;
        $charging = Charging::when($status === "inactive", function ($query) {
                $query->onlyTrashed();
            })
            ->useFilters()
            ->dynamicPaginate();

        if ($charging->isEmpty()) {
            return $this->responseSuccess("No Charging Found", $charging);
        }

        if (!$pagination) {
            ChargingResource::collection($charging);
        } else {
            $charging = ChargingResource::collection($charging);
        }

        return $this->responseSuccess("Charging retrieved successfully", $charging);
    }

    public function store(ChargingRequest $request)
    {
        $charging = Charging::create($request->validated());

        return $this->responseCreated(
            'Charging created successfully',
            new ChargingResource($charging)
        );
    }

    public function update(ChargingRequest $request, int $id)
    {
        $charging = Charging::withTrashed()->find($id);

        if (! $charging) {
            return $this->responseNotFound(null, 'Charging not found');
        }

        $charging->update($request->validated());

        return $this->responseSuccess(
            'Charging updated successfully',
            new ChargingResource($charging)
        );
    }

    public function destroy(int $id)
    {
        $charging = Charging::withTrashed()->find($id);

        if (! $charging) {
            return $this->responseNotFound(null, 'Charging not found');
        }

        if ($charging->trashed()) {
            $charging->restore();

            return $this->responseSuccess(
                'Charging restored successfully',
                new ChargingResource($charging)
            );
        }

        $charging->delete();

        return $this->responseSuccess(
            'Charging deleted successfully',
            null
        );
    }
}

