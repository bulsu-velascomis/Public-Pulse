<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChargingController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SurveyFormController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::ApiResource("/users", UserController::class);
    Route::ApiResource("/roles", RoleController::class);
    Route::ApiResource("/section_header", SectionController::class);
    Route::ApiResource("/questionnaire", QuestionnaireController::class);
    Route::ApiResource("/answer", AnswerController::class);
    Route::ApiResource("/survey_form", SurveyFormController::class);
    Route::post("/submit_survey", [
        SurveyFormController::class,
        "submitSurvey",
    ]);
    Route::get("/submitted_survey", [
        SurveyFormController::class,
        "submittedSurvey",
    ]);
    Route::ApiResource("/charging", ChargingController::class);
    Route::put("/change_password", [UserController::class, "changePassword"]);
    Route::put("/reset_password", [AuthController::class, "resetPassword"]);
    Route::ApiResource("/record", RecordController::class);
    Route::ApiResource("/report", ReportController::class);
});

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);
