<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        // Create Sanctum tokena
        $tokenResult = $user->createToken('frontend-token', [], now()->addDays(1));
        // $tokenResult = $user->createToken('frontend-token', [], now()->addMinutes(2));
        $token = $tokenResult->plainTextToken;


        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully.',
            'token' => $token, // <-- frontend will save this
           'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->name, // <-- just the role name
                    ]
   
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully.',
        ], 200);
    }
}
