<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'id' => $admin->id,
            'username' => $admin->username,
            'email' => $admin->email,
            'token' => $token
        ]);
    }

public function me(Request $request)
{
    $admin = $request->user('admin'); 

    if (!$admin) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    return response()->json([
        'username' => $admin->username,
        'email' => $admin->email,
        'role' => 'admin'
    ]);
}

public function logout(Request $request)
{
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['message' => 'No token provided'], 400);
    }

    $admin = Admin::where('api_token', hash('sha256', $token))->first();

    if ($admin) {
        $admin->api_token = null;
        $admin->save();
    }

    return response()->json(['message' => 'Admin logged out successfully']);
}



}
