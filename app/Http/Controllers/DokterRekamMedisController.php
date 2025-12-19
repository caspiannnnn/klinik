<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Pendaftaran;
use App\Models\RekamMedis;

class DokterRekamMedisController extends Controller
{
    public function pasienIndex()
    {
        $dokter = Auth::user();

        $pendaftars = Pendaftaran::query()
            ->where('status', 'diterima')
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Pastikan view data pasien yang ada dipakai
        $viewCandidates = [
            'dokter.data_pasien',
            'dokter.pasien',
            'dokter.rekam_medis.index',
            'dokter.rekam-medis.index',
        ];

        foreach ($viewCandidates as $v) {
            if (View::exists($v)) {
                return view($v, compact('pendaftars'));
            }
        }

        abort(500, 'View data pasien tidak ditemukan.');
    }

    public function daftarIndex()
    {
        $dokter = Auth::user();

        $rekamMedisList = RekamMedis::with('pendaftaran')
            ->where('dokter_id', $dokter->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokter.daftar_rekam_medis', compact('rekamMedisList'));
    }

    public function show($id)
    {
        $dokter = Auth::user();

        $pendaftaran = Pendaftaran::query()->findOrFail($id);

        // ✅ hanya boleh input jika diterima
        if ((string) ($pendaftaran->status ?? '') !== 'diterima') {
            abort(403, 'Pendaftaran belum berstatus diterima.');
        }

        // ✅ pastikan pendaftaran memang untuk dokter ini
        if (
            !empty($pendaftaran->dokter_id)
            && (int) $pendaftaran->dokter_id !== (int) $dokter->id
            && (int) ($pendaftaran->diterima_oleh_dokter_id ?? 0) !== (int) $dokter->id
        ) {
            abort(403, 'Pendaftaran ini bukan untuk Anda.');
        }

        // ✅ Cari view form rekam medis yang benar (berdasarkan file yang biasanya ada)
        $viewCandidates = [
            'dokter.rekam_medis',                 // misal: resources/views/dokter/rekam_medis.blade.php
            'dokter.rekam_medis.rekam_medis',     // misal: resources/views/dokter/rekam_medis/rekam_medis.blade.php
            'dokter.rekam_medis.form',
            'dokter.rekam-medis.rekam_medis',
            'dokter.rekam-medis.form',
        ];

        foreach ($viewCandidates as $v) {
            if (View::exists($v)) {
                return view($v, compact('pendaftaran'));
            }
        }

        abort(500, 'View form rekam medis tidak ditemukan.');
    }

    public function store(Request $request, $id)
    {
        $dokter = Auth::user();

        $pendaftaran = Pendaftaran::query()->findOrFail($id);

        if ((string) ($pendaftaran->status ?? '') !== 'diterima') {
            abort(403, 'Pendaftaran belum berstatus diterima.');
        }

        if (
            !empty($pendaftaran->dokter_id)
            && (int) $pendaftaran->dokter_id !== (int) $dokter->id
            && (int) ($pendaftaran->diterima_oleh_dokter_id ?? 0) !== (int) $dokter->id
        ) {
            abort(403, 'Pendaftaran ini bukan untuk Anda.');
        }

        $request->validate([
            'diagnosa' => 'required|string',
            'tindakan' => 'required|string',
            'resep'    => 'nullable|string',
            'catatan'  => 'nullable|string',
        ]);

        RekamMedis::create([
            'pendaftaran_id' => $pendaftaran->id,
            'pasien_id'      => $pendaftaran->user_id,
            'dokter_id'      => $dokter->id,
            'diagnosa'       => $request->diagnosa,
            'tindakan'       => $request->tindakan,
            'resep'          => $request->resep,
            'catatan'        => $request->catatan,
            'tanggal'        => now()->toDateString(),
        ]);

        return redirect()->route('dokter.daftar_rekam_medis')->with('success', 'Rekam medis berhasil disimpan.');
    }
}
