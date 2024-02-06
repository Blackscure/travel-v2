<?php

namespace App\Http\Controllers;

use App\Models\TravelAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TravelAgentController extends Controller
{
    /**
     * Register a new travel agent.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:travel_agents',
                'password' => 'required|min:6',
            ]);

            // Create a new travel agent
            $travelAgent = TravelAgent::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Return a success response with travel agent data
            return response()->json(['message' => 'Registration successful', 'travel_agent' => $travelAgent], 201);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Log in a travel agent.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            \Log::info('Login Attempt', ['email' => $credentials['email']]);

            if (Auth::attempt($credentials)) {
                $accessToken = Auth::user()->createToken('authToken')->accessToken;
                return response()->json(['message' => 'Login successful', 'access_token' => $accessToken]);
            }

            \Log::warning('Login Failed', ['email' => $credentials['email']]);
            throw ValidationException::withMessages(['message' => 'Invalid login credentials']);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Login failed', 'message' => $e->getMessage()], 401);
        }
    }



}
