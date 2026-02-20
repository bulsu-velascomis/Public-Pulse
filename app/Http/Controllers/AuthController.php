<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(UserRequest $request)
    {
        $validated = $request->validated();

        $password = $validated["password"] ?? ($validated["username"] ?? null);
        if (!$password) {
            return $this->responseError(
                "Username or password is required",
                null
            );
        }

        $user = User::create([
            "firstname" => $validated["firstname"],
            "middlename" => $validated["middlename"],
            "lastname" => $validated["lastname"],
            "suffix" => $validated["suffix"],
            "username" => $validated["username"],
            "password" => Hash::make($password),
            "role_id" => $validated["role_id"] ?? 2,
            "charging_id" => $validated["charging_id"],
        ]);

        $token = $user->createToken("auth_token")->plainTextToken;

        $user->load("role", "charging");

        return $this->responseCreated(
            "User registered successfully",
            new UserResource($user)
        );
    }

    public function login(Request $request)
    {
        $user = User::where("username", $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                "username" => ["The provided credentials are incorrect."],
                "password" => ["The provided credentials are incorrect."],
            ]);

            if ($user || Hash::check($request->password, $user->username)) {
                return $this->responseError("Invalid Credentials", null);
            }
        }
        $token = $user->createToken("PersonalAccessToken")->plainTextToken;
        $user["token"] = $token;

        $cookie = cookie("auth_token", $token);

        $user->load("role", "charging");

        return $this->responseSuccess("Login Succesful", $user)->withCookie(
            $cookie
        );
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        $cookie = cookie()->forget("auth_token");

        return response()
            ->json(["message" => "Logged out successfully"])
            ->withCookie($cookie);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where("username", $request->username)->first();

        if (!$user) {
            return $this->responseNotFound("User not found", null);
        }

        $resetPassword = $user->username;

        $user->update([
            "password" => Hash::make($resetPassword),
        ]);

        return $this->responseSuccess(
            "Password reset successfully to its default password.", $user
        );
    }
}
