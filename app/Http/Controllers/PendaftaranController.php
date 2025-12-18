<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftaran;
use App\Models\User;

class PendaftaranController extends Controller
{
    public function create()
    {
        // ✅ Fallback cari view yang memang ada di project
        $candidates = [
            'pasien.pendaftaran',        // resources/views/pasien/pendaftaran.blade.php
            'pasien.pendaftaran.create', // resources/views/pasien/pendaftaran/create.blade.php
            'pendaftaran.create',        // resources/views/pendaftaran/create.blade.php
            'pasien.daftar',             // resources/views/pasien/daftar.blade.php
            'pasien.pendaftaran_form',   // kalau kamu pakai nama lain
        ];

        foreach ($candidates as $v) {
            if (view()->exists($v)) {
                return view($v);
            }
        }

        // Kalau masih tidak ketemu, biar errornya jelas
        abort(500, 'View pendaftaran tidak ditemukan. Pastikan ada salah satu: ' . implode(', ', $candidates));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp'         => ['required', 'regex:/^[0-9]{9,15}$/'],
            'nik'           => ['required', 'regex:/^[0-9]{16}$/'],
            'keluhan'       => 'required|string',
        ]);

        $user = Auth::user();

        // ✅ Sinkron data user biar prefill konsisten
        User::where('id', $user->id)->update([
            'name'          => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp'         => $request->no_hp,
            'nik'           => $request->nik,
        ]);

        Pendaftaran::create([
            'user_id'       => $user->id,
            'nama'          => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp'         => $request->no_hp,
            'nik'           => $request->nik,
            'keluhan'       => $request->keluhan,
            'status'        => Pendaftaran::STATUS_MENUNGGU, // ✅ lowercase sesuai migrasi
        ]);

        // ✅ balik ke form + popup (session success)
        return redirect()->route('pendaftaran.create')
            ->with('success', 'Pendaftaran berhasil dikirim. Silakan tunggu konfirmasi dokter.');
    }

    // kalau route ini kamu pakai, biar gak error 404:
    public function success($id)
    {
        // optional: bisa redirect saja
        return redirect()->route('pendaftaran.create')->with('success', 'Pendaftaran berhasil.');
    }

    public function checkin($token)
    {
        // kalau fitur checkin belum dipakai sekarang, minimal jangan blank
        $p = Pendaftaran::where('qr_token', $token)->firstOrFail();
        $p->update(['checkin_at' => now()]);
        return redirect()->back()->with('success', 'Check-in berhasil.');
    }
}
