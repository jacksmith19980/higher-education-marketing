<?php

use Illuminate\Http\Request;
use App\Tenant\Models\Application;
use App\Http\Controllers\Api\ApplicationsApiController;
use App\Http\Controllers\Api\SubmissionsApiController;
use App\Http\Controllers\Api\StudentsApiController;

Route::prefix('/{school}/')->group(function () {

    // Applications
    Route::get('applications' , [ApplicationsApiController::class, 'index']);
    Route::put('applications/{application}/update' , [ApplicationsApiController::class, 'update']);

    // Submission
    Route::get('submissions' , [SubmissionsApiController::class, 'index']);

    Route::get('submissions/{submission}' , [SubmissionsApiController::class, 'show']);

    Route::put('submissions/{submission}/update' , [SubmissionsApiController::class, 'update']);


    // Students
    Route::get('students' , [StudentsApiController::class, 'index']);
    Route::get('students/{student}' , [StudentsApiController::class, 'show']);




});
