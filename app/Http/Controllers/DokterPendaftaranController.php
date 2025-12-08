<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Notifikasi;

class DokterPendaftaranController extends Controller
{
    public function index()
    {
        // Biar dokter tidak lihat data yang "ngacak", kita tampilkan yang relevan dulu.
        // Kalau kamu mau semua, ganti ->whereIn(...) jadi ->latest()
        $pendaftars = Pendaftaran::whereIn('status', ['Menunggu', 'Diterima'])
            ->latest()
            ->get();

        return view('dokter.pendaftar', compact('pendaftars'));
    }

    public function show($id)
    {
        $pendaftar = Pendaftaran::findOrFail($id);
        return view('dokter.pendaftar.show', compact('pendaftar'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diterima,Ditolak',
        ]);

        $pendaftar = Pendaftaran::findOrFail($id);

        // simpan status lama untuk pesan notifikasi
        $oldStatus = $pendaftar->status;

        $pendaftar->status = $request->status;
        $pendaftar->save();

        // ✅ Buat notifikasi untuk pasien terkait (kalau ada user_id)
        if (!empty($pendaftar->user_id)) {
            $judul = 'Status Pendaftaran Diperbarui';

            // pesan dibuat jelas + informatif
            $pesan = "Status pendaftaran Anda berubah dari {$oldStatus} menjadi {$pendaftar->status}.";

            Notifikasi::create([
                'user_id' => $pendaftar->user_id,
                'judul'   => $judul,
                'pesan'   => $pesan,
                'tipe'    => 'pendaftaran',
                // kamu bisa arahkan ke halaman kartu pasien atau pendaftaran sukses/riwayat
                'link'    => route('pasien.kartu'),
                'dibaca'  => false,
            ]);
        }

        return back()->with('success', 'Status berhasil diperbarui.');
    }
}
