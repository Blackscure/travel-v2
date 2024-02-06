<?php


namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AccommodationController extends Controller
{
    public function index()
    {
        return Accommodation::all();
    }

    public function show($id)
    {
        return Accommodation::findOrFail($id);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'standard_rack_rate' => 'required|numeric',
            ]);

            // Attempt to create a new Accommodation
            $accommodation = Accommodation::create($request->all());

            // Return a success response
            return response()->json(['message' => 'Accommodation created successfully', 'accommodation' => $accommodation], 201);
        } catch (QueryException $e) {
            // Handle database query exception
            return response()->json(['error' => 'Accommodation creation failed', 'message' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Accommodation creation failed', 'message' => $e->getMessage()], 400);
        }
    }


    public function update(Request $request, $id)
    {
        $accommodation = Accommodation::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'standard_rack_rate' => 'required|numeric',
            // Add validation for other fields as needed
        ]);

        $accommodation->update($request->all());

        return $accommodation;
    }

    public function destroy($id)
    {
        $accommodation = Accommodation::findOrFail($id);
        $accommodation->delete();

        return ['message' => 'Accommodation deleted successfully'];
    }
}
