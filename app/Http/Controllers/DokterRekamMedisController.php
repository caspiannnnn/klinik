<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\RekamMedis;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokterRekamMedisController extends Controller
{
    /**
     * Tampilkan daftar semua rekam medis yang sudah diinput dokter
     * (untuk halaman: /dokter/daftar-rekam-medis)
     */
    public function index()
    {
        $rekamMedisList = RekamMedis::with(['pendaftaran', 'dokter'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokter.daftar_rekam_medis', compact('rekamMedisList'));
    }

    /**
     * Tampilkan form/halaman input rekam medis untuk satu pendaftaran
     */
    public function show($id)
    {
        $pendaftaran = Pendaftaran::with('user')->findOrFail($id);

        $rekamTerakhir = RekamMedis::with(['dokter', 'pendaftaran'])
            ->where('pendaftaran_id', $id)
            ->latest()
            ->first();

        return view('dokter.rekam_medis', compact('pendaftaran', 'rekamTerakhir'));
    }

    /**
     * Simpan rekam medis untuk pendaftaran tertentu
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'diagnosa' => 'required|string|max:255',
            'tindakan' => 'required|string|max:255',
            'catatan'  => 'nullable|string',
        ]);

        $pendaftaran = Pendaftaran::with('user')->findOrFail($id);

        $rekam = RekamMedis::create([
            'pendaftaran_id' => $pendaftaran->id,
            'dokter_id'      => Auth::id(),
            'diagnosa'       => $request->diagnosa,
            'tindakan'       => $request->tindakan,
            'catatan'        => $request->catatan,
        ]);

        // ✅ Notifikasi ke pasien (berdasarkan user_id dari pendaftaran)
        if (!empty($pendaftaran->user_id)) {
            Notifikasi::create([
                'user_id' => $pendaftaran->user_id,
                'judul'   => 'Rekam Medis Baru Ditambahkan',
                'pesan'   => 'Dokter telah menambahkan rekam medis untuk kunjungan Anda pada ' . $rekam->created_at->format('d-m-Y H:i') . '.',
                'tipe'    => 'rekam_medis',
                'link'    => route('pasien.rekam_medis'),
                'dibaca'  => false,
            ]);
        }

        return redirect()->back()->with('success', 'Rekam medis berhasil disimpan.');
    }
}
