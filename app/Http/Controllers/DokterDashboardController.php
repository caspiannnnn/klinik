<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Carbon\Carbon;

class DokterDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // ✅ Jadwal hari ini = pendaftaran yang statusnya "diterima"
        // dan statusnya diubah (updated_at) pada hari ini.
        $jadwalHariIni = Pendaftaran::with('user')
            ->where(function ($q) {
                $q->where('status', 'diterima')
                  ->orWhere('status', 'Diterima')
                  ->orWhere('status', 'DITERIMA');
            })
            ->whereDate('updated_at', $today)
            ->orderBy('updated_at', 'asc')
            ->get();

        $totalJadwal = $jadwalHariIni->count();

        // Total pasien unik yang pernah diterima (sepanjang waktu)
        $totalPasien = Pendaftaran::where(function ($q) {
                $q->where('status', 'diterima')
                  ->orWhere('status', 'Diterima')
                  ->orWhere('status', 'DITERIMA');
            })
            ->distinct('user_id')
            ->count('user_id');

        // Total konsultasi = total pendaftaran diterima (sepanjang waktu)
        $totalKonsultasi = Pendaftaran::where(function ($q) {
                $q->where('status', 'diterima')
                  ->orWhere('status', 'Diterima')
                  ->orWhere('status', 'DITERIMA');
            })
            ->count();

        // ✅ file view kamu adalah resources/views/dokter/index.blade.php
        return view('dokter.index', compact(
            'totalPasien',
            'totalJadwal',
            'totalKonsultasi',
            'jadwalHariIni'
        ));
    }
}
