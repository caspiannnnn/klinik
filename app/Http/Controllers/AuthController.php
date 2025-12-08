<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect('/admin');
                case 'dokter':
                    return redirect('/dokter');
                case 'pasien':
                    return redirect('/pasien');
                case 'resepsionis':
                    return redirect('/resepsionis');
                default:
                    return redirect('/');
            }
        }

        return back()
            ->withErrors(['email' => 'Login gagal. Email atau password salah.'])
            ->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:6',

            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp'         => 'required|string|max:20',
            'nik'           => 'required|digits:16',

            // ✅ alamat dari form
            'alamat'        => 'required|string|max:255',
        ]);

        User::create([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'pasien',

            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp'         => $request->no_hp,
            'nik'           => $request->nik,
            'alamat'        => $request->alamat,
        ]);

        return redirect()->route('login.form')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
