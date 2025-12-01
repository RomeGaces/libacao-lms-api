<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // 🔹 Step 1: Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error_message' => 'Validation failed',
                    'data' => $validator->errors(),
                ], 422);
            }

            // 🔹 Step 2: Attempt authentication
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'error_message' => 'Invalid credentials',
                ], 401);
            }

            // 🔹 Step 3: Generate token
            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
        } catch (\Throwable $e) {
            // 🔹 Step 4: Catch any unexpected errors
            return response()->json([
                'error_message' => 'An unexpected error occurred',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ], 500);
        }
    }




    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Token revoked']);
        } catch (\Throwable $e) {
            return apiResponse(500, 'An unexpected error occurred', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
