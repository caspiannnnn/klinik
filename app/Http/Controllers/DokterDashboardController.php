<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Pendaftaran;

class DokterDashboardController extends Controller
{
    public function index()
    {
        $dokter = Auth::user();
        $now = Carbon::now();

        /**
         * ✅ Minggu dimulai Senin 00:00 (auto “ganti minggu” saat Senin 00:00)
         * Minggu ini  : Senin - Minggu
         * Minggu depan: Senin - Minggu berikutnya
         */
        $startThisWeek = $now->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $endThisWeek   = $now->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();

        $startNextWeek = $startThisWeek->copy()->addWeek();
        $endNextWeek   = $endThisWeek->copy()->addWeek();

        // ✅ Jadwal Minggu Ini
        $jadwalMingguIni = Pendaftaran::with('user')
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->whereBetween('tanggal_kunjungan', [
                $startThisWeek->toDateString(),
                $endThisWeek->toDateString(),
            ])
            ->orderBy('tanggal_kunjungan')
            ->orderBy('jam_kunjungan')
            ->get();

        // ✅ Jadwal Minggu Depan
        $jadwalMingguDepan = Pendaftaran::with('user')
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->whereBetween('tanggal_kunjungan', [
                $startNextWeek->toDateString(),
                $endNextWeek->toDateString(),
            ])
            ->orderBy('tanggal_kunjungan')
            ->orderBy('jam_kunjungan')
            ->get();

        // ✅ Total Pasien (unik berdasarkan user_id)
        $totalPasien = Pendaftaran::query()
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        // ✅ Jadwal Hari Ini (tanggal_kunjungan = hari ini)
        $totalJadwal = Pendaftaran::query()
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->whereDate('tanggal_kunjungan', $now->toDateString())
            ->count();

        // ✅ Total Konsultasi (jumlah pendaftaran milik dokter ini)
        $totalKonsultasi = Pendaftaran::query()
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->count();

        // ✅ Pastikan nama view sesuai file yang kamu pakai
        // Jika file kamu ada di: resources/views/dokter/index.blade.php
        return view('dokter.index', [
            'totalPasien' => $totalPasien,
            'totalJadwal' => $totalJadwal,
            'totalKonsultasi' => $totalKonsultasi,
            'jadwalMingguIni' => $jadwalMingguIni,
            'jadwalMingguDepan' => $jadwalMingguDepan,
        ]);
    }
}
