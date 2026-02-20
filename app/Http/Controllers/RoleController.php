<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisplayRequest;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;
use Essa\APIToolKit\Api\ApiResponse;

class RoleController extends Controller
{
    use ApiResponse;

    public function assignRole(RoleRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update(["role_id" => $validated["role_id"]]);

        return $this->responseSuccess(
            "Role assigned successfully",
            [$user->load("role")],
            200
        );
    }

    public function index(DisplayRequest $request)
    {
        $status = $request->query("status");
        $pagination = $request->pagination;
        $role = Role::when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })
            ->useFilters()
            ->dynamicPaginate();

        if ($role->isEmpty()) {
            return $this->responseSuccess("No Role Found", $role);
        }

        if (!$pagination) {
            RoleResource::collection($role);
        } else {
            $role = RoleResource::collection($role);
        }

        return $this->responseSuccess("Roles retrieved successfully", $role);
    }

    public function store(RoleRequest $request)
    {
        $data = $request->validated();

        $role = Role::create([
            "name" => $data["name"],
            "access_permission" => $data["access_permission"],
        ]);

        return $this->responseCreated(
            "Role created successfully",
            new RoleResource($role)
        );
    }

    public function update(RoleRequest $request, int $id)
    {
        $role = Role::withTrashed()->find($id);

        if (!$role) {
            return $this->responseNotFound("Role not found", $role);
        }

        $role->update($request->validated());

        return $this->responseSuccess(
            "Role updated successfully",
            new RoleResource($role)
        );
    }

    public function destroy(int $id)
    {
        $role = Role::withTrashed()->find($id);

        if (!$role) {
            return $this->responseNotFound(null, "Role not found");
        }

        if ($role->trashed()) {
            $role->restore();

            return $this->responseSuccess(
                "Role restored successfully",
                new RoleResource($role)
            );
        }

        $role->delete();

        return $this->responseSuccess("Role deleted successfully", null);
    }
}
