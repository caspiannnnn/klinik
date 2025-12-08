<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Pembayaran;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'totalPasien' => Pendaftaran::count(),
            'totalDokter' => User::where('role', 'dokter')->count(),
            'totalResepsionis' => User::where('role', 'resepsionis')->count(),
            'totalKeuangan' => Pembayaran::where('status', 'lunas')->sum('jumlah'),
        ]);
    }
}