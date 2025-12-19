<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Dokter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PendaftaranApiController extends Controller
{
    public function index()
    {
        $data = Pendaftaran::with(['pasien', 'dokter'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pasien_id'       => 'required|exists:pasiens,id',
            'dokter_id'       => 'required|exists:dokters,id',
            'tanggal'         => 'required|date',
            'jam'             => 'required',
            'keluhan'         => 'nullable|string',
            'status'          => 'nullable|string',
            'metode_bayar'    => 'nullable|string',
            'total_bayar'     => 'nullable|numeric',
        ]);

        $pasien = Pasien::findOrFail($validated['pasien_id']);
        $dokter = Dokter::findOrFail($validated['dokter_id']);

        $pendaftaran = Pendaftaran::create([
            'pasien_id'    => $pasien->id,
            'dokter_id'    => $dokter->id,
            'tanggal'      => $validated['tanggal'],
            'jam'          => $validated['jam'],
            'keluhan'      => $validated['keluhan'] ?? null,
            'status'       => $validated['status'] ?? 'menunggu',
            'kode_daftar'  => Str::upper(Str::random(8)),
            'metode_bayar' => $validated['metode_bayar'] ?? null,
            'total_bayar'  => $validated['total_bayar'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dibuat',
            'data' => $pendaftaran->load(['pasien', 'dokter']),
        ], 201);
    }

    public function show(Pendaftaran $pendaftaran)
    {
        return response()->json([
            'success' => true,
            'data' => $pendaftaran->load(['pasien', 'dokter']),
        ]);
    }

    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate([
            'pasien_id'       => 'nullable|exists:pasiens,id',
            'dokter_id'       => 'nullable|exists:dokters,id',
            'tanggal'         => 'nullable|date',
            'jam'             => 'nullable',
            'keluhan'         => 'nullable|string',
            'status'          => 'nullable|string',
            'metode_bayar'    => 'nullable|string',
            'total_bayar'     => 'nullable|numeric',
        ]);

        $pendaftaran->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil diperbarui',
            'data' => $pendaftaran->fresh()->load(['pasien', 'dokter']),
        ]);
    }

    public function destroy(Pendaftaran $pendaftaran)
    {
        $pendaftaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dihapus',
        ]);
    }

    public function byUser($user_id)
    {
        $data = Pendaftaran::whereHas('pasien', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->with(['pasien', 'dokter'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function uploadBukti(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate([
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $validated['bukti_bayar'];
        $filename = 'bukti_bayar/' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        Storage::disk('public')->put($filename, file_get_contents($file));

        $pendaftaran->bukti_bayar = $filename;
        $pendaftaran->save();

        return response()->json([
            'success' => true,
            'message' => 'Bukti bayar berhasil diupload',
            'data' => $pendaftaran,
        ]);
    }
}
