<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /* ------------------------------------
       GET: List users
    ------------------------------------ */
    public function index()
    {
        return response()->json(User::latest()->get());
    }

    /* ------------------------------------
       POST: Create user
    ------------------------------------ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => bcrypt($validated['password']),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data'    => $user
        ], 201);
    }

    /* ------------------------------------
       GET: Single user
    ------------------------------------ */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /* ------------------------------------
       PUT/PATCH: Update user
    ------------------------------------ */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password'   => 'sometimes|nullable|string|min:6',
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name  = $validated['last_name'];
        $user->email      = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return response()->json($user);
    }

    /* ------------------------------------
       DELETE: Remove user
    ------------------------------------ */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
