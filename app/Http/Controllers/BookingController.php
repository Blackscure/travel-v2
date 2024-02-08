<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return response()->json($bookings, Response::HTTP_OK);
    }

    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($booking, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'accommodation_id' => 'required',
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $booking = Booking::create($request->all());

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
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
