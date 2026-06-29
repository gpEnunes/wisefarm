<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\FieldController;
use Illuminate\Support\Facades\Route;

// Health check — public
Route::get('/ping', fn () => response()->json(['status' => 'ok', 'project' => 'WiseFarm']));

// Auth — public
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    Route::apiResource('farms',        FarmController::class);
    Route::apiResource('farms.fields', FieldController::class);

    Route::get('/crops', [CropController::class, 'index']);
});
