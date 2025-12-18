<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminPasienController extends Controller
{
    // Menampilkan daftar pasien
    public function index()
    {
        $pasiens = User::where('role', 'pasien')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pasien.index', compact('pasiens'));
    }

    // Menampilkan form tambah pasien
    public function create()
    {
        return view('admin.pasien.create');
    }

    // Menyimpan data pasien baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'no_hp' => ['required', 'regex:/^[0-9]{9,15}$/'],
            'nik' => ['required', 'regex:/^[0-9]{16}$/', 'unique:users,nik'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'pasien',
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'nik' => $request->nik,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.pasien.index')->with('success', 'Pasien berhasil ditambahkan.');
    }

    // ✅ Form edit pasien
    public function edit($id)
    {
        $pasien = User::where('role', 'pasien')->findOrFail($id);
        return view('admin.pasien.edit', compact('pasien'));
    }

    // ✅ Update pasien
    public function update(Request $request, $id)
    {
        $pasien = User::where('role', 'pasien')->findOrFail($id);

        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $pasien->id,
            'username'      => 'required|string|max:50|unique:users,username,' . $pasien->id,

            // ✅ TAMBAH VALIDASI INI
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp'         => ['required', 'regex:/^[0-9]{9,15}$/'],
            'nik'           => ['required', 'regex:/^[0-9]{16}$/', 'unique:users,nik,' . $pasien->id],

            'password'      => 'nullable|string|min:6',
        ]);

        $pasien->name = $request->nama;
        $pasien->email = $request->email;
        $pasien->username = $request->username;

        // ✅ UPDATE FIELD TAMBAHAN
        $pasien->tanggal_lahir = $request->tanggal_lahir;
        $pasien->jenis_kelamin = $request->jenis_kelamin;
        $pasien->no_hp = $request->no_hp;
        $pasien->nik = $request->nik;

        // Password opsional: update hanya jika diisi
        if ($request->filled('password')) {
            $pasien->password = Hash::make($request->password);
        }

        $pasien->save();

        return redirect()->route('admin.pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    // ✅ Hapus pasien
    public function destroy($id)
    {
        $pasien = User::where('role', 'pasien')->findOrFail($id);
        $pasien->delete();

        return redirect()->route('admin.pasien.index')->with('success', 'Pasien berhasil dihapus.');
    }
}
