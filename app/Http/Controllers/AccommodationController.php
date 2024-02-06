<?php


namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'standard_rack_rate' => 'required|numeric',
            // Add validation for other fields as needed
        ]);

        return Accommodation::create($request->all());
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
