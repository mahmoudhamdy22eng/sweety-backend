<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            // Validate user_type if present
            'user_type' => 'sometimes|string|in:admin,supplier,customer', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Default to 'customer' if not provided
        $userType = $request->input('user_type', 'customer'); 

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'user_type' => $userType, // Assign user_type
        ]);

        // $user->sendEmailVerificationNotification();

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['message' => 'User registered successfully. Please check your email for verification.', 'token' => $token, 'user' => $user], 201);
    }
}

