<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => 'nullable|string|max:255',
            'username'      => 'nullable|string|max:255',
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'password'      => 'nullable|string|min:6|confirmed',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string',
            'no_hp'         => 'nullable|string|max:20',
            'nik'           => 'nullable|string|max:30',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'spesialis'     => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            if ($key === 'password' && $value) {
                $user->password = Hash::make($value);
                continue;
            }

            if ($value !== null) {
                $user->{$key} = $value;
            }
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ]);
    }
}
