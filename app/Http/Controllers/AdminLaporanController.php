<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'bulan'); // default sebulan
        $allowed = ['minggu', 'bulan', 'tahun', 'semua'];

        if (!in_array($periode, $allowed, true)) {
            $periode = 'bulan';
        }

        $to = now();
        $from = null;

        if ($periode === 'minggu') {
            $from = now()->copy()->subDays(7);
        } elseif ($periode === 'bulan') {
            $from = now()->copy()->subMonth();
        } elseif ($periode === 'tahun') {
            $from = now()->copy()->subYear();
        } elseif ($periode === 'semua') {
            $from = null;
        }

        // Anggap pemasukan = pembayaran yang sudah "lunas/dibayar/dikonfirmasi/paid"
        // (biar aman sama variasi penamaan status di project kamu)
        $paidStatuses = [
            'lunas', 'dibayar', 'dikonfirmasi', 'paid', 'confirmed', 'success', 'selesai'
        ];

        $baseQuery = Pembayaran::query()
            ->whereRaw("LOWER(status) IN (" . collect($paidStatuses)->map(fn($s) => "'" . $s . "'")->implode(',') . ")");

        if ($from) {
            $baseQuery->whereBetween('created_at', [$from, $to]);
        }

        // Rekap per pasien (sum jumlah)
        $laporan = (clone $baseQuery)
            ->select('user_id', DB::raw('SUM(jumlah) as total'), DB::raw('MAX(created_at) as last_paid_at'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->get();

        // Total keseluruhan pemasukan
        $totalKeseluruhan = (clone $baseQuery)->sum('jumlah');

        return view('admin.laporan.index', [
            'laporan' => $laporan,
            'periode' => $periode,
            'from' => $from,
            'to' => $to,
            'totalKeseluruhan' => $totalKeseluruhan,
        ]);
    }
}
