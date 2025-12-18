<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RekamMedisApiController extends Controller
{
    public function index()
    {
        $data = RekamMedis::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_id' => ['required', 'integer', 'exists:pendaftarans,id'],
            'diagnosa' => ['required', 'string'],
            'tindakan' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
        ]);

        // ✅ AUTO: dokter_id dari user login (sanctum)
        $validated['dokter_id'] = $request->user()->id;

        $rekam = RekamMedis::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil dibuat.',
            'data' => $rekam,
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $rekam = RekamMedis::find($id);

        if (!$rekam) {
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rekam,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $rekam = RekamMedis::find($id);

        if (!$rekam) {
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'pendaftaran_id' => ['sometimes', 'integer', 'exists:pendaftarans,id'],
            'diagnosa' => ['sometimes', 'string'],
            'tindakan' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
        ]);

        $rekam->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil diperbarui.',
            'data' => $rekam,
        ], 200);
    }

    public function destroy($id)
    {
        $rekam = RekamMedis::find($id);

        if (!$rekam) {
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan.',
            ], 404);
        }

        $rekam->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil dihapus.',
        ], 200);
    }

    public function byPatient($user_id)
    {
        $data = RekamMedis::whereHas('pendaftaran', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }
}
