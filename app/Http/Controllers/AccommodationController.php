<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Accommodation;

class AccommodationController extends Controller
{
    // Retrieve all accommodations
    public function index()
    {
        $accommodations = Accommodation::all();
        return response()->json($accommodations, Response::HTTP_OK);
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
   
    public function create_accomodation(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'standard_rack_rate' => 'required|numeric',
            ]);

            // Create a new accommodation
            $accommodation = Accommodation::create($request->all());

            // Return the created accommodation with a success response
            return response()->json($accommodation, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process
            return response()->json(['error' => 'Accommodation creation failed', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    // Update an existing accommodation by ID
    public function update(Request $request, $id)
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
