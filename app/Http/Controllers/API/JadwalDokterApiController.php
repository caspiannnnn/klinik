<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalDokter;

class JadwalDokterApiController extends Controller
{
    public function index()
    {
        $data = JadwalDokter::with('dokter')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'hari'      => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal = JadwalDokter::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal dokter berhasil dibuat',
            'data' => $jadwal->load('dokter'),
        ], 201);
    }

    public function show(JadwalDokter $jadwalDokter)
    {
        return response()->json([
            'success' => true,
            'data' => $jadwalDokter->load('dokter'),
        ]);
    }

    public function update(Request $request, JadwalDokter $jadwalDokter)
    {
        $validated = $request->validate([
            'dokter_id' => 'nullable|exists:dokters,id',
            'hari'      => 'nullable|string',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
        ]);

        $jadwalDokter->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal dokter berhasil diperbarui',
            'data' => $jadwalDokter->fresh()->load('dokter'),
        ]);
    }

    public function destroy(JadwalDokter $jadwalDokter)
    {
        $jadwalDokter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal dokter berhasil dihapus',
        ]);
    }

    public function byDoctor($dokter_id)
    {
        $data = JadwalDokter::where('dokter_id', $dokter_id)
            ->with('dokter')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
