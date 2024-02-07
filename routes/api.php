<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccommodationController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\ContractController;

// Existing Sanctum route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Registration and Login routes

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/accommodations', [AccommodationController::class, 'create_accommodation']);
Route::get('/accommodations', [AccommodationController::class, 'index']);
Route::get('/accommodations/{id}', [AccommodationController::class, 'show']);
Route::put('/accommodations/{id}', [AccommodationController::class, 'update']);
Route::delete('/accommodations/{id}', [AccommodationController::class, 'destroy']);

Route::apiResource('contracts', ContractController::class);
