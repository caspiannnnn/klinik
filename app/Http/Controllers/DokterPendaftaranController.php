<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class DokterPendaftaranController extends Controller
{
    public function index()
    {
        // kamu bisa pakai ->hariIni() kalau mau yang hari ini saja
        $pendaftars = Pendaftaran::orderBy('created_at', 'desc')->get();

        return view('dokter.pendaftar', compact('pendaftars'));
    }

    public function show($id)
    {
        $pendaftar = Pendaftaran::with('user')->findOrFail($id);
        return view('dokter.pendaftaran.show', compact('pendaftar'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,datang,selesai',
        ]);

        $p = Pendaftaran::findOrFail($id);
        $old = (string) $p->status;
        $new = (string) $request->status;

        $p->update([
            'status' => $new,
        ]);

        // notif ke pasien kalau status berubah
        if ($p->user_id && strtolower($old) !== strtolower($new)) {
            Notifikasi::create([
                'user_id' => $p->user_id,
                'judul'   => 'Status Pendaftaran',
                'pesan'   => 'Status pendaftaran Anda berubah dari "' . $old . '" menjadi "' . $new . '".',
                'tipe'    => 'pendaftaran',
                'link'    => route('pasien.notifikasi'),
                'dibaca'  => false,
            ]);
        }

        return back();
    }
}
