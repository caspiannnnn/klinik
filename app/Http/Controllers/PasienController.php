<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PasienController extends Controller
{
    public function __construct()
    {
        // Semua endpoint butuh login
        $this->middleware('auth');
    }

    public function kartu()
    {
        $user = Auth::user();

        if ($user->role !== 'pasien') {
            abort(403, 'Hanya pasien yang dapat melihat kartu pasien.');
        }

        if (empty($user->no_rm)) {
            $user->no_rm = 'RM-' . now()->format('Ym') . '-' . str_pad((string) $user->id, 5, '0', STR_PAD_LEFT);
        }

        if (empty($user->qr_token)) {
            $user->qr_token = (string) Str::uuid();
        }

        $scanUrl = route('pasien.scan', $user->qr_token);

        /**
         * ✅ FIX: Jangan biarkan error Imagick menjalar.
         * - Coba generate PNG (kalau imagick/gd tersedia)
         * - Kalau gagal (imagick tidak ada), fallback ke SVG
         * - Path disesuaikan dengan ekstensi yang berhasil
         */
        $preferredPngPath = "patient_qr/{$user->qr_token}.png";
        $fallbackSvgPath  = "patient_qr/{$user->qr_token}.svg";

        // kalau belum ada qr_path atau file-nya hilang, generate ulang
        $needGenerate = empty($user->qr_path) || !Storage::disk('public')->exists($user->qr_path);

        if ($needGenerate) {
            try {
                // ✅ coba PNG dulu
                $png = QrCode::format('png')->size(250)->margin(1)->generate($scanUrl);
                Storage::disk('public')->put($preferredPngPath, $png);
                $user->qr_path = $preferredPngPath;
            } catch (\Throwable $e) {
                // ✅ fallback SVG (tidak butuh imagick)
                $svg = QrCode::format('svg')->size(250)->margin(1)->generate($scanUrl);
                Storage::disk('public')->put($fallbackSvgPath, $svg);
                $user->qr_path = $fallbackSvgPath;
            }
        }

        $user->save();

        return view('pasien.kartu', compact('user'));
    }

    public function scan(string $token)
    {
        $viewer = Auth::user();

        if (!in_array($viewer->role, ['dokter', 'resepsionis', 'admin'])) {
            abort(403, 'Tidak berwenang memindai kartu pasien.');
        }

        // Temukan pasien dari token QR
        $pasien = User::where('qr_token', $token)->firstOrFail();

        // Riwayat pendaftaran pasien
        $pendaftarans = Pendaftaran::where('user_id', $pasien->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // ✅ Ambil rekam medis pasien (berdasarkan pendaftaran milik pasien)
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
