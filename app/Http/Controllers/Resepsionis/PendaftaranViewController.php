<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PendaftaranViewController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::with('user')->latest()->get();
        return view('resepsionis.pendaftaran.index', compact('pendaftarans'));
    }

    public function cetak($id)
    {
        $pendaftaran = Pendaftaran::with('user')->findOrFail($id);
        return view('resepsionis.pendaftaran.cetak', compact('pendaftaran'));
    }
}