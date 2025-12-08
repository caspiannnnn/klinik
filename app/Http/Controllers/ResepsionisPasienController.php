<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ResepsionisPasienController extends Controller
{
    public function create()
    {
        return view('resepsionis.daftar_pasien');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:6',
            'alamat'        => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'nik'           => 'required|digits:16',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'keluhan'       => 'required|string',
        ]);

        // 1) Buat akun pasien
        $user = User::create([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'pasien',

            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
            'nik'           => $request->nik,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        // 2) Generate No RM jika belum ada
        if (empty($user->no_rm)) {
            $user->no_rm = 'RM-' . now()->format('Ym') . '-' . str_pad((string) $user->id, 5, '0', STR_PAD_LEFT);
        }

        // 3) Generate token QR jika belum ada
        if (empty($user->qr_token)) {
            $user->qr_token = (string) Str::uuid();
        }

        // 4) Generate QR (SVG) untuk kartu pasien (scan oleh staf)
        $scanUrl = route('pasien.scan', $user->qr_token);
        $path = $user->qr_path ?: "patient_qr/{$user->qr_token}.svg";

        if (!$user->qr_path || !Storage::disk('public')->exists($user->qr_path)) {
            $svg = QrCode::format('svg')->size(250)->margin(1)->generate($scanUrl);
            Storage::disk('public')->put($path, $svg);
            $user->qr_path = $path;
        }

        $user->save();

        // 5) Buat pendaftaran yang terhubung ke pasien (biar rekam medis nanti nyambung)
        $today     = now()->toDateString();
        $last      = Pendaftaran::whereDate('created_at', $today)->max('nomor_urut');
        $nomorUrut = ($last ?? 0) + 1;

        $kodeAntrian = 'A' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        Pendaftaran::create([
            'user_id'       => $user->id,
            'nama'          => $user->name,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp'         => $request->no_hp,
            'keluhan'       => $request->keluhan,
            'status'        => 'menunggu',

            'nomor_urut'    => $nomorUrut,
            'kode_antrian'  => $kodeAntrian,
        ]);

        // 6) Tampilkan kartu pasien versi cetak (tab baru karena form target=_blank)
        return view('resepsionis.kartu_pasien', ['user' => $user]);
    }
}
