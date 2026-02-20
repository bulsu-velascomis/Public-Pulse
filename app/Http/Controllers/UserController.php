<?php

namespace App\Http\Controllers;

use App\Models\User;
//use App\Filters\UserFilter;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\DisplayRequest;
use App\Http\Requests\ChangePasswordRequest;
//use Illuminate\Database\Eloquent\Collection;

class UserController extends Controller
{
    use ApiResponse;

    public function index(DisplayRequest $request)
    {
        $status = $request->query("status");
        $pagination = $request->pagination;
        $users = User::with("role", "charging")
            ->when($status === "inactive", function ($query) {
                $query->onlyTrashed();
            })
            ->useFilters()
            ->dynamicPaginate();

        if ($users->isEmpty()) {
            return $this->responseSuccess("No User Found", $users);
        }

        if (!$pagination) {
            UserResource::collection($users);
        } else {
            $users = UserResource::collection($users);
        }

        return $this->responseSuccess("Users retrieved successfully", $users);
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->responseNotFound(null, "User not found");
        }

        $validated = $request->validated();

        if (isset($validated["password"])) {
            $validated["password"] = bcrypt($validated["password"]);
        }

        $user->update($validated);
        $user->load(["role", "charging"]);

        return $this->responseSuccess(
            "User updated successfully",
            new UserResource($user)
        );
    }

    public function destroy($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return $this->responseNotFound(null, "User not found");
        }

        $is_active = User::withTrashed()
            ->where("id", $id)
            ->first();

        if (!$is_active) {
            return $is_active;
        } elseif (!$is_active->deleted_at) {
            $is_active->delete();
            $message = "User deleted successfully";
        } else {
            $is_active->restore();
            $message = "User restored successfully";
        }

        return $this->responseSuccess($message, $user);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->responseUnprocessable(
                "Current password is incorrect."
            );
        } elseif ($request->current_password === $request->new_password) {
            return $this->responseUnprocessable(
                "New password cannot be the same as the current password."
            );
        } elseif ($request->new_password === $user->username) {
            return $this->responseUnprocessable(
                "New password cannot be the same as the username."
            );
        }

        $user->update([
            "password" => Hash::make($request->new_password),
        ]);

        return $this->responseSuccess("Password changed successfully.", $user);
    }

    // public function changePassword(ChangePasswordRequest $request)
    // {
    //     $user = $request->user();

    //     $user->update([
    //         "password" => Hash::make($request->new_password),
    //     ]);

    //     return response()->json([
    //         "message" => "Password changed successfully.",
    //     ]);
    // }
}
