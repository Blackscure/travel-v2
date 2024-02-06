<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    }

    // login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Authenticate the travel agent using the 'travel_agents' guard
        if (Auth::guard('travel_agents')->attempt($credentials)) {
            $travelAgent = Auth::guard('travel_agents')->user();
            $accessToken = $travelAgent->createToken('authToken')->accessToken;

            return response()->json(['message' => 'Login successful', 'access_token' => $accessToken]);
        }

        return response()->json(['error' => 'Invalid login credentials'], 401);
    }


}
