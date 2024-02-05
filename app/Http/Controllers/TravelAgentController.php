<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TravelAgentController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:travel_agents',
        'password' => 'required|min:6',
    ]);

    $travelAgent = TravelAgent::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return response()->json(['message' => 'Registration successful', 'travel_agent' => $travelAgent], 201);
}

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response()->json(['message' => 'Login successful', 'access_token' => $accessToken]);
    }

    throw ValidationException::withMessages(['message' => 'Invalid login credentials']);
}

}
