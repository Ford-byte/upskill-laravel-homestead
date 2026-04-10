<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }
public function login(Request $request)
{
    $validated = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt($validated)) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    $user = Auth::user(); 

    $user->load('role'); 

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'user' => $user,
        'token' => $token,
        'role' => $user->role?->name,
    ]);
}

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    public function editUser(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }

    $validated = $request->validate([
        'name' => ['sometimes', 'string', 'max:255'],
        'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'password' => ['sometimes', 'string', 'min:8'],
    ]);

    if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }

    $user->update($validated);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user->fresh(),
    ]);
}

public function getUsers()
{
    $users = User::all();

    if ($users->isEmpty()) {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }

    return response()->json($users);
}
}