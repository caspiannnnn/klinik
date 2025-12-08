<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DokterDashboardController extends Controller
{
    public function index()
    {
        // Total pasien dengan status diterima
        $totalPasien = Pendaftaran::where('status', 'Diterima')->count();

        // Gunakan created_at untuk ambil jadwal hari ini
        $jadwalHariIni = Pendaftaran::whereDate('created_at', Carbon::today())->get();
        $totalJadwal = $jadwalHariIni->count();

        return view('dokter.index', [
            'totalPasien' => $totalPasien,
            'totalJadwal' => $totalJadwal,
            'jadwalHariIni' => $jadwalHariIni,
        ]);
    }
}