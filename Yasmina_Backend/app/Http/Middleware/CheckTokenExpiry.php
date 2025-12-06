<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenExpiry
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()?->currentAccessToken();

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: token not found.'
            ], 401);
        }

        
      if ($token->expires_at && now()->greaterThan($token->expires_at)) {           
            $token->delete();

            return response()->json([
                'status' => 'error',
                'message' => 'Token expired. Please login again.'
            ], 401);
        }

        return $next($request);
    }
}
