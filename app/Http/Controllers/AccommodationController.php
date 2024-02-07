<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Accommodation;

class AccommodationController extends Controller
{
    // Retrieve all accommodations
   // Retrieve all accommodations
public function index()
{
    try {
        $accommodations = Accommodation::all();

        // Wrap the response in a 'data' array
        return response()->json(['data' => $accommodations], Response::HTTP_OK);
    } catch (\Exception $e) {
        // Handle any exceptions that occur during the process
        return response()->json(['error' => 'Error retrieving accommodations', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    // Retrieve a specific accommodation by ID
    public function show($id)
    {
        $accommodation = Accommodation::find($id);

        if (!$accommodation) {
            return response()->json(['error' => 'Accommodation not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($accommodation, Response::HTTP_OK);
    }

    // Create a new accommodation

    public function create_accommodation(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'standard_rack_rate' => 'required|numeric',
            ]);

            // Check if the accommodation with the given name already exists
            $existingAccommodation = Accommodation::where('name', $request->input('name'))->first();

            if ($existingAccommodation) {
                return response()->json(['error' => 'Accommodation already exists', 'data' => $existingAccommodation], Response::HTTP_CONFLICT);
            }

            // Create a new accommodation
            $accommodation = Accommodation::create($request->all());

            // Return the created accommodation with a success response
            return response()->json([
                'message' => 'Accommodation created successfully',
                'data' => $accommodation,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process
            return response()->json(['error' => 'Accommodation creation failed', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }







    // Update an existing accommodation by ID
    public function update_accomodation(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'standard_rack_rate' => 'required|numeric',
        ]);

        $accommodation = Accommodation::find($id);

        if (!$accommodation) {
            return response()->json(['error' => 'Accommodation not found'], Response::HTTP_NOT_FOUND);
        }

        $accommodation->update($request->all());

        return response()->json($accommodation, Response::HTTP_OK);
    }

    // Delete an accommodation by ID
    public function destroy($id)
    {
        $accommodation = Accommodation::find($id);

        if (!$accommodation) {
            return response()->json(['error' => 'Accommodation not found'], Response::HTTP_NOT_FOUND);
        }

        $accommodation->delete();

        return response()->json(['message' => 'Accommodation deleted successfully'], Response::HTTP_OK);
    }
}
