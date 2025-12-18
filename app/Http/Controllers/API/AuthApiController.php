<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * REGISTER PASIEN
     * Route: POST /api/auth/register
     */
    public function registerPasien(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // kalau user kamu punya kolom role, set default pasien
        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ];

        // opsional: kalau ada kolom role di tabel users
        if (\Schema::hasColumn('users', 'role')) {
            $userData['role'] = 'pasien';
        }

        $user = User::create($userData);

        // Buat token supaya Postman enak
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Register berhasil.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * LOGIN
     * Route: POST /api/auth/login
     * Body yang diterima fleksibel:
     * - { "login": "email/username", "password": "..." }
     * atau
     * - { "username": "email/username", "password": "..." }  (punya kamu sekarang)
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['sometimes', 'string'],
            'username' => ['sometimes', 'string'],
            'email' => ['sometimes', 'string'],
            'password' => ['required', 'string'],
        ]);

        // ambil identifier dari field yang tersedia
        $identifier = $validated['login']
            ?? $validated['username']
            ?? $validated['email']
            ?? null;

        if (!$identifier) {
            throw ValidationException::withMessages([
                'login' => ['Field login/username/email wajib diisi.'],
            ]);
        }

        // cari user by email atau username
        $user = User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Email/username atau password salah.'],
            ]);
        }

        // Optional: hapus token lama biar bersih (boleh kamu hapus kalau nggak mau)
        // $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    /**
     * ME
     * Route: GET /api/auth/me (auth:sanctum)
     */
    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // token string yang dikirim Postman di header Authorization
        $bearerToken = $request->bearerToken();

        // info token (id, name, abilities, created_at, last_used_at)
        $tokenModel = $user->currentAccessToken();

        return response()->json([
            'success' => true,
            'message' => 'Data user.',
            'data' => [
                'user' => $user,
                // ini hanya akan ada kalau request pakai Authorization: Bearer ...
                'token' => $bearerToken,
                'token_meta' => $tokenModel ? [
                    'id' => $tokenModel->id,
                    'name' => $tokenModel->name,
                    'abilities' => $tokenModel->abilities,
                    'created_at' => optional($tokenModel->created_at)->toDateTimeString(),
                    'last_used_at' => optional($tokenModel->last_used_at)->toDateTimeString(),
                ] : null,
            ],
        ], 200);
    }

    /**
     * LOGOUT
     * Route: POST /api/auth/logout (auth:sanctum)
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // revoke token yang sedang dipakai request ini
        $currentToken = $user->currentAccessToken();
        if ($currentToken) {
            $currentToken->delete();
        } else {
            // fallback: kalau somehow pakai session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ], 200);
    }
}
