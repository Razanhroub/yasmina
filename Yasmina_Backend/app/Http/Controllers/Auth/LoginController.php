<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ], 401);
        }

        // Login using Sanctum (cookie-based)
        Auth::login($user);
        // // Regenerate session for security
        // $request->session()->regenerate();


        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->name,
            ]
        ], 200);
    }
     
    public function logout(Request $request): JsonResponse
    {
        // Logout user
        Auth::logout();

        // Destroy session
        $request->session()->invalidate();

        // Generate new CSRF token for next requests
        $request->session()->regenerateToken();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully.',
        ], 200);
    }


    
    
}
