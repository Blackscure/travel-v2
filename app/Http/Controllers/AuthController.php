<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['message' => 'User registered successfully', 'access_token' => $token], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;

            return response()->json(['message' => 'Login successful', 'access_token' => $token]);
        }

        return response()->json(['error' => 'Invalid login credentials'], 401);
    }

    public function getAllUsers()
    {
        try {
            $users = User::all();
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch users', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->save();

            return response()->json(['message' => 'User updated successfully', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User update failed', 'message' => $e->getMessage()], 500);
        }
    }

    // Del method
    public function deleteUser($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $user->delete();

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User deletion failed', 'message' => $e->getMessage()], 500);
        }
    }
}
