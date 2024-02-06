<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $contracts = Contract::all();
        return response()->json(['contracts' => $contracts], 200);
    }

    public function show($id)
    {
        $contract = Contract::findOrFail($id);
        return response()->json(['contract' => $contract], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
            'travel_agent_id' => 'required|exists:travel_agents,id',
            'contract_rates' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            // Add other validation rules
        ]);

        $contract = Contract::create($request->all());

        return response()->json(['contract' => $contract], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'accommodation_id' => 'exists:accommodations,id',
            'travel_agent_id' => 'exists:travel_agents,id',
            'contract_rates' => 'numeric',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            // Add other validation rules
        ]);

        $contract = Contract::findOrFail($id);
        $contract->update($request->all());

        return response()->json(['contract' => $contract], 200);
    }

    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return response()->json(['message' => 'Contract deleted successfully'], 200);
    }
}
