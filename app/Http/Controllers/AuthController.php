<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100|min:3',
            'email'    => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //Rango Bronce por defecto
        $bronceRank = Rank::where('name', 'Bronce')->first();

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rank_id'  => $bronceRank?->id,
        ]);

        //Rol cliente por defecto
        $clienteRole = Role::where('name', 'cliente')->first();
        if ($clienteRole) {
            $user->roles()->attach($clienteRole, ['assigned_at' => now()]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user->load('roles', 'rank'),
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Los datos son incorrectos']
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user->load('roles', 'rank'),
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada satisfactoriamente',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user()->load('roles', 'rank'));
    }

    public function updateProfile(Request $request)
{
    $user = $request->user();

    $request->validate([
        'name'     => 'required|string|max:100',
        'email'    => 'required|string|email|max:100|unique:users,email,' . $user->id,
        'phone'    => 'nullable|string|max:20',
        'whatsapp' => 'nullable|string|max:20',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    $user->name     = $request->name;
    $user->email    = $request->email;
    $user->phone    = $request->phone;
    $user->whatsapp = $request->whatsapp;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json([
        'message' => 'Perfil actualizado correctamente',
        'user'    => $user->load('roles', 'rank'),
    ]);
}
}