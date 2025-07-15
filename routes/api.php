<?php

use App\Http\Controllers\Api\AcademicyearController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\GradesubjectController;
use App\Http\Controllers\Api\RuleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\StudentController;
//Route::apiResource('students',StudentController::class);
Route::post("register",[ApiController::class,"register"]);
Route::post("login",[ApiController::class,"login"]);
Route::group(["middleware"=>["auth:sanctum"]],function(){
    Route::get("profile",[ApiController::class,"profile"]);
    Route::get("logout",[ApiController::class,"logout"]);
    Route::apiResource('gradesubjects',GradesubjectController::class);
Route::apiResource('grades',GradeController::class);
Route::apiResource('academicyears',AcademicyearController::class);

});
//Route::apiResource('gradesubjects',GradesubjectController::class);
//Route::apiResource('grades',GradeController::class);
//Route::apiResource('academicyears',AcademicyearController::class);

