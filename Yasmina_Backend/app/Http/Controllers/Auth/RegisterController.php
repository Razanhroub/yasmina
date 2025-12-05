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

        // Create user with default role_id = 3 (student)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => Role::where('name', 'student')->first()->id,
        ]);

        // Assign Spatie role
        $user->assignRole('student');

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully. Please login to continue.',
            'user' => $user
        ], 201);
    }
}
