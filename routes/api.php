<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractController;

// Existing Sanctum route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Registration and Login routes

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/users', [AuthController::class, 'getAllUsers']); // New route for getting all users

Route::put('/users/{id}', [AuthController::class, 'updateUser']); // New route for updating a user

Route::delete('/users/{id}', [AuthController::class, 'deleteUser']); // New route for deleting a user

Route::post('/accommodations', [AccommodationController::class, 'create_accommodation']);
Route::get('/accommodations', [AccommodationController::class, 'index']);
Route::get('/accommodations/{id}', [AccommodationController::class, 'show']);
Route::put('/accommodations/{id}', [AccommodationController::class, 'update_accomodation']);
Route::delete('/accommodations/{id}', [AccommodationController::class, 'destroy']);

Route::get('/bookings', [BookingController::class, 'get_bookings']);          // Get all bookings
Route::get('/bookings/{id}', [BookingController::class, 'show']);      // Get a specific booking
Route::post('/create-bookings', [BookingController::class, 'create_booking']);          // Create a new booking
Route::put('/bookings/{id}', [BookingController::class, 'update_booking']);     // Update a booking
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']); // Delete a booking

Route::apiResource('contracts', ContractController::class);
