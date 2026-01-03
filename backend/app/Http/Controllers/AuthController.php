<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate API token
        $apiToken = Str::random(60);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => hash('sha256', $apiToken), // Store hashed token
        ]);

        return response()->json([
            'success' => true, 
            'user' => $user,
            'token' => $apiToken, // Return plain token to frontend
            'message' => 'Registration successful'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check credentials
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        
        // Generate new API token
        $apiToken = Str::random(60);
        $user->api_token = hash('sha256', $apiToken);
        $user->save();

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $apiToken, // Return token to frontend
            'message' => 'Login successful'
        ]);
    }

    // Add this method
  public function me(Request $request)
{
    // Get token from Authorization header
    $token = $request->bearerToken();

    // Hash it (as your auth middleware does)
    $hashed = $token ? hash('sha256', $token) : null;

    // Find user by token hash
    $user = $token ? User::where('api_token', $hashed)->first() : null;

    // Return debug information (temporary)
    return response()->json([
        'success' => (bool) $user,
        'message' => $user ? 'Token valid' : 'Invalid or missing token',
        'received_token' => $token,           // raw token sent by client (for debugging)
        'received_token_sha256' => $hashed,   // server-side hash of the token
        'db_token' => $user ? $user->api_token : null, // value stored in DB
        'user' => $user
    ]);
}

    public function logout(Request $request)
    {
        // Get token from header
        $token = $request->bearerToken();
        
        if ($token) {
            $user = User::where('api_token', hash('sha256', $token))->first();
            if ($user) {
                $user->api_token = null;
                $user->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}