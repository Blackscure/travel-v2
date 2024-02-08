<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    public function get_bookings()
    {
        try {
            // Retrieve bookings with associated user and accommodation information
            $bookings = Booking::with(['user', 'accommodation'])->get();

            // Return the JSON response with the bookings wrapped in a 'data' array
            return response()->json(['data' => $bookings], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process
            return response()->json(['error' => 'Failed to retrieve bookings', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($booking, Response::HTTP_OK);
    }

    public function create_booking(Request $request)
    {
        try {
            $request->validate([
                'accommodation_id' => 'required',
                'user_id' => 'required', // Update to 'travel_agent_id'
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            // Check if a booking with the same accommodation, travel_agent, and overlapping dates already exists
            $existingBooking = Booking::where('accommodation_id', $request->accommodation_id)
                ->where('user_id', $request->user_id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                })
                ->first();

            if ($existingBooking) {
                return response()->json([
                    'error' => 'Booking already exists for the selected accommodation and dates',
                    'data' => $existingBooking,
                ], Response::HTTP_CONFLICT);
            }

            // If no existing booking found, create a new booking
            $booking = Booking::create($request->all());

            return response()->json([
                'message' => 'Booking created successfully',
                'data' => $booking,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Booking creation failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update_booking(Request $request, $id)
    {
        $request->validate([
            'accommodation_id' => 'required',
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $booking->update($request->all());

        return response()->json([
            'message' => 'Booking updated successfully',
            'data' => $booking,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully'], Response::HTTP_OK);
    }
}
