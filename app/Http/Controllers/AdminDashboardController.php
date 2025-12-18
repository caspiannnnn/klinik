<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPasien = User::where('role', 'pasien')->count();
        $totalDokter = User::where('role', 'dokter')->count();
        $totalResepsionis = User::where('role', 'resepsionis')->count();

        // ✅ Kolom uang: jumlah
        $totalKeuangan = (int) DB::table('pembayarans')->sum('jumlah');

        return view('admin.index', compact(
            'totalPasien',
            'totalDokter',
            'totalResepsionis',
            'totalKeuangan'
        ));
    }
}
