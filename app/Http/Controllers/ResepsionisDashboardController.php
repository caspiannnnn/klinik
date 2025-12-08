<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ResepsionisDashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // ✅ Total seluruh pasien (bukan hari ini)
        $totalPasien = User::where('role', 'pasien')->count();

        // Pendaftaran baru hari ini (status menunggu)
        $pendaftaranBaru = Pendaftaran::whereDate('created_at', $today)
            ->where('status', 'menunggu')
            ->count();

        // Dokter aktif
        $dokterAktif = User::where('role', 'dokter')->count();

        // Jadwal hari ini (sesuaikan kolom "tanggal" jika berbeda)
        $jadwalHariIni = 0;
        try {
            $jadwalHariIni = DB::table('jadwal_dokters')
                ->whereDate('tanggal', $today)
                ->count();
        } catch (\Throwable $e) {
            $jadwalHariIni = 0;
        }

        return view('resepsionis.index', compact(
            'totalPasien',
            'pendaftaranBaru',
            'dokterAktif',
            'jadwalHariIni'
        ));
    }
}
