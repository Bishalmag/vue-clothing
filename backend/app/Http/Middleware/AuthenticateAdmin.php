<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Admin token not provided. Please login first.'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || !$accessToken->tokenable instanceof \App\Models\Admin) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $request->admin = $accessToken->tokenable;

        return $next($request);
    }
}
