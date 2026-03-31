<?php

use App\Http\Controllers\Api\AnalysisController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Analysis routes
    Route::post('/analyze', [AnalysisController::class, 'store'])->middleware('throttle:analyze');
    Route::get('/history', [AnalysisController::class, 'index']);
    Route::get('/analyses/{analysis}', [AnalysisController::class, 'show']);
    Route::delete('/analyses/{analysis}', [AnalysisController::class, 'destroy']);
});

// Auth routes without middleware
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
