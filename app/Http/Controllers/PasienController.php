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

        $path = $user->qr_path ?: "patient_qr/{$user->qr_token}.svg";

        if (!$user->qr_path || !Storage::disk('public')->exists($user->qr_path)) {
            $svg = QrCode::format('svg')->size(250)->margin(1)->generate($scanUrl);
            Storage::disk('public')->put($path, $svg);
            $user->qr_path = $path;
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
