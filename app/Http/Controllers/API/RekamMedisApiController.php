<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamMedis;

class RekamMedisApiController extends Controller
{
    public function index()
    {
        $data = RekamMedis::with(['pasien', 'dokter'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pasien_id'  => 'required|exists:pasiens,id',
            'dokter_id'  => 'required|exists:dokters,id',
            'diagnosa'   => 'required|string',
            'tindakan'   => 'required|string',
            'resep'      => 'nullable|string',
            'catatan'    => 'nullable|string',
            'tanggal'    => 'required|date',
        ]);

        $rekamMedis = RekamMedis::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil dibuat',
            'data' => $rekamMedis->load(['pasien', 'dokter']),
        ], 201);
    }

    public function show(RekamMedis $rekamMedis)
    {
        return response()->json([
            'success' => true,
            'data' => $rekamMedis->load(['pasien', 'dokter']),
        ]);
    }

    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $validated = $request->validate([
            'pasien_id'  => 'nullable|exists:pasiens,id',
            'dokter_id'  => 'nullable|exists:dokters,id',
            'diagnosa'   => 'nullable|string',
            'tindakan'   => 'nullable|string',
            'resep'      => 'nullable|string',
            'catatan'    => 'nullable|string',
            'tanggal'    => 'nullable|date',
        ]);

        $rekamMedis->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil diperbarui',
            'data' => $rekamMedis->fresh()->load(['pasien', 'dokter']),
        ]);
    }

    public function destroy(RekamMedis $rekamMedis)
    {
        $rekamMedis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil dihapus',
        ]);
    }

    public function byPasien($pasien_id)
    {
        $data = RekamMedis::where('pasien_id', $pasien_id)
            ->with(['pasien', 'dokter'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
