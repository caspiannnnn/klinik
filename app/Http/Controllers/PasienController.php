<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function kartu()
    {
        $user = Auth::user();

        if ($user->role !== 'pasien') {
            abort(403, 'Hanya pasien yang dapat melihat kartu pasien.');
        }

        // No RM auto jika kosong
        if (empty($user->no_rm)) {
            $user->no_rm = 'RM-' . now()->format('Ym') . '-' . str_pad((string) $user->id, 5, '0', STR_PAD_LEFT);
        }

        // Token QR auto jika kosong
        if (empty($user->qr_token)) {
            $user->qr_token = (string) Str::uuid();
        }

        // QR berisi URL scan pasien
        $scanUrl = route('pasien.scan', $user->qr_token);

        // ✅ Simpan QR sebagai PNG (lebih kompatibel untuk scan kamera/upload)
        $path = $user->qr_path ?: "patient_qr/{$user->qr_token}.png";

        if (!$user->qr_path || !Storage::disk('public')->exists($user->qr_path)) {
            $png = QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->generate($scanUrl);

            Storage::disk('public')->put($path, $png);
            $user->qr_path = $path;
        }

        $user->save();

        // ✅ Pastikan view punya url QR yang valid (PNG)
        // Jika User model belum punya accessor qr_url, ini tetap aman dipakai di blade:
        $user->qr_url = asset('storage/' . $user->qr_path);

        return view('pasien.kartu', compact('user'));
    }

    public function scan(string $token)
    {
        $viewer = Auth::user();

        // ✅ Kalau belum login, arahkan ke login (bukan 403)
        if (!$viewer) {
            return redirect()->route('login.form');
        }

        // ✅ Kalau bukan staf, arahkan ke dashboard masing-masing (bukan 403)
        if (!in_array($viewer->role, ['dokter', 'resepsionis', 'admin'])) {
            // pasien tidak boleh lihat data pasien lain
            // arahkan ke halaman pasien
            return redirect('/pasien')->with('error', 'Anda tidak berwenang memindai kartu pasien.');
        }

        // Temukan pasien dari token QR
        $pasien = User::where('qr_token', $token)->firstOrFail();

        // Riwayat pendaftaran pasien
        $pendaftarans = Pendaftaran::where('user_id', $pasien->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // Rekam medis pasien
        $rekamMedisList = RekamMedis::with(['dokter', 'pendaftaran'])
            ->whereHas('pendaftaran', function ($q) use ($pasien) {
                $q->where('user_id', $pasien->id);
            })
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return view('resepsionis.scan_pasien', compact('pasien', 'pendaftarans', 'rekamMedisList'));
    }
}
