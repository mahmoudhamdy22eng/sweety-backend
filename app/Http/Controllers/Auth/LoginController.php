<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'remember_me' => 'boolean'
        ]);

        // Retrieve user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists, password matches, and user is not deleted
        if (!$user || !Hash::check($request->password, $user->password) || $user->is_deleted) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }

        // Create access token for user
        $token = $user->createToken('authToken')->accessToken;

        // Prepare user data to return
        $userData = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_type' => $user->user_type,
        ];

        // Check if user is an admin
        $isAdmin = ($user->user_type === 'admin');

        // Add additional information if user is admin
        $userData['is_admin'] = $isAdmin;

        // Return response with token and user data
        return response()->json(['status' => true, 'token' => $token, 'user' => $userData], 200);
    }

    public function logout(Request $request)
    {
        // Revoke user's token
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
