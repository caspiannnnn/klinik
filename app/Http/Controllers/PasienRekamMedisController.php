<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth;

class PasienRekamMedisController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $userName = Auth::user()->name;

        $rekamMedisList = RekamMedis::with(['dokter', 'pendaftaran.user'])
            ->whereHas('pendaftaran', function ($query) use ($userId, $userName) {
                $query->where('user_id', $userId)
                      ->orWhere(function ($q) use ($userName) {
                          $q->whereNull('user_id')
                            ->where('nama', $userName);
                      });
            })
            ->latest()
            ->get();

        return view('pasien.rekam_medis', compact('rekamMedisList'));
    }
}
