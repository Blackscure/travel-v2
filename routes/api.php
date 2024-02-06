<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelAgentController;
use App\Http\Controllers\AccommodationController;

// Existing Sanctum route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Registration and Login routes
Route::post('/register', [TravelAgentController::class, 'register']);
Route::post('/login', [TravelAgentController::class, 'login']);
Route::resource('accommodations', AccommodationController::class);
