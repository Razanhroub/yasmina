<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        // Get validated data
        $data = $request->validated();

        $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    // Assign default role
    $user->assignRole('student');
    // Create Sanctum tokena
        $tokenResult = $user->createToken('frontend-token');
        $token = $tokenResult->plainTextToken;

        // Add expiration manually
        $user->tokens()->latest('id')->first()->update([
            'expires_at' => now()->addDay(),
        ]);

       

    return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully.',
            'token' => $token, 
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(), // gets Spatie role
        ], 201);

    }
}
