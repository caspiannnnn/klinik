<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDokterController extends Controller
{
    public function index()
    {
        $dokters = User::where('role', 'dokter')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        return view('admin.dokter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'username'  => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'spesialis' => 'required|string|max:255',
            'password'  => 'required|string|min:6',
        ]);

        User::create([
            'name'      => $request->nama,
            'username'  => $request->username,
            'email'     => $request->email,
            'spesialis' => $request->spesialis,
            'password'  => Hash::make($request->password),
            'role'      => 'dokter',
        ]);

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    // ✅ Tambahan: Form edit dokter
    public function edit($id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        return view('admin.dokter.edit', compact('dokter'));
    }

    // ✅ Tambahan: Update dokter
    public function update(Request $request, $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'username'  => 'required|string|max:50|alpha_dash|unique:users,username,' . $dokter->id,
            'email'     => 'required|email|unique:users,email,' . $dokter->id,
            'spesialis' => 'required|string|max:255',
            'password'  => 'nullable|string|min:6',
        ]);

        $dokter->name = $request->nama;
        $dokter->username = $request->username;
        $dokter->email = $request->email;
        $dokter->spesialis = $request->spesialis;

        // Password opsional: hanya update kalau diisi
        if ($request->filled('password')) {
            $dokter->password = Hash::make($request->password);
        }

        $dokter->save();

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    // ✅ Tambahan: Hapus dokter
    public function destroy($id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        $dokter->delete();

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil dihapus.');
    }
}
