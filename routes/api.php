<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/wellness/dashboard', [\App\Http\Controllers\Api\V1\WellnessApiController::class, 'dashboard']);
    Route::post('/mood', [\App\Http\Controllers\Api\V1\WellnessApiController::class, 'logMood']);
    Route::get('/activities', [\App\Http\Controllers\Api\V1\WellnessApiController::class, 'getActivities']);
    Route::post('/activities/complete', [\App\Http\Controllers\Api\V1\WellnessApiController::class, 'completeActivity']);
});
