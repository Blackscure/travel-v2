<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccommodationController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::all();
        return response()->json($accommodations, Response::HTTP_OK);
    }

    public function show($id)
    {
        $accommodation = Accommodation::find($id);

        if (!$accommodation) {
            return response()->json(['error' => 'Accommodation not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($accommodation, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'standard_rack_rate' => 'required|numeric',
        ]);

        $accommodation = Accommodation::create($request->all());
        return response()->json($accommodation, Response::HTTP_CREATED);
    }

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
