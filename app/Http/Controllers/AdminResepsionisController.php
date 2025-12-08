<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminResepsionisController extends Controller
{
    public function index()
    {
        $resepsionis = User::where('role', 'resepsionis')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.resepsionis.index', compact('resepsionis'));
    }

    public function create()
    {
        return view('admin.resepsionis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'resepsionis',
        ]);

        return redirect()->route('admin.resepsionis.index')
            ->with('success', 'Resepsionis berhasil ditambahkan.');
    }

    // ✅ Form edit resepsionis
    public function edit($id)
    {
        $user = User::where('role', 'resepsionis')->findOrFail($id);
        return view('admin.resepsionis.edit', compact('user'));
    }

    // ✅ Update resepsionis
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'resepsionis')->findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->nama;
        $user->email = $request->email;
        $user->username = $request->username;

        // Password opsional: update hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.resepsionis.index')
            ->with('success', 'Data resepsionis berhasil diperbarui.');
    }

    // ✅ Hapus resepsionis
    public function destroy($id)
    {
        $user = User::where('role', 'resepsionis')->findOrFail($id);
        $user->delete();

        return redirect()->route('admin.resepsionis.index')
            ->with('success', 'Resepsionis berhasil dihapus.');
    }
}
