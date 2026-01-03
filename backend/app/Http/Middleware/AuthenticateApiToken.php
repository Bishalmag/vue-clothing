<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 401);
        }

        $user = User::where('api_token', hash('sha256', $token))->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        // Attach user to request
        auth()->setUser($user);

        return $next($request);
    }
}