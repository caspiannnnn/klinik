<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'username'        => 'nullable|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6|confirmed',
            'role'            => 'required|string',
            'tanggal_lahir'   => 'nullable|date',
            'jenis_kelamin'   => 'nullable|string',
            'no_hp'           => 'nullable|string|max:20',
            'nik'             => 'nullable|string|max:30',
            'alamat'          => 'nullable|string',
            'telepon'         => 'nullable|string|max:20',
            'spesialis'       => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name'          => $validated['name'],
            'username'      => $validated['username'] ?? null,
            'email'         => $validated['email'],
            'role'          => $validated['role'],
            'spesialis'     => $validated['spesialis'] ?? null,
            'password'      => Hash::make($validated['password']),
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'no_hp'         => $validated['no_hp'] ?? null,
            'nik'           => $validated['nik'] ?? null,
            'alamat'        => $validated['alamat'] ?? null,
            'telepon'       => $validated['telepon'] ?? null,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Register berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal. Email atau password salah.',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }
}
