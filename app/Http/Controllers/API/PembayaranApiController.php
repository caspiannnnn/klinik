<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PembayaranApiController extends Controller
{
    public function index()
    {
        $data = Pembayaran::with(['pasien', 'pendaftaran'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'metode'         => 'required|string',
            'jumlah'         => 'required|numeric',
            'status'         => 'nullable|string',
        ]);

        $pembayaran = Pembayaran::create([
            'pendaftaran_id' => $validated['pendaftaran_id'],
            'pasien_id'      => $validated['pasien_id'],
            'metode'         => $validated['metode'],
            'jumlah'         => $validated['jumlah'],
            'status'         => $validated['status'] ?? 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dibuat',
            'data' => $pembayaran->load(['pasien', 'pendaftaran']),
        ], 201);
    }

    public function show(Pembayaran $pembayaran)
    {
        return response()->json([
            'success' => true,
            'data' => $pembayaran->load(['pasien', 'pendaftaran']),
        ]);
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'nullable|exists:pendaftarans,id',
            'pasien_id'      => 'nullable|exists:pasiens,id',
            'metode'         => 'nullable|string',
            'jumlah'         => 'nullable|numeric',
            'status'         => 'nullable|string',
        ]);

        $pembayaran->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diperbarui',
            'data' => $pembayaran->fresh()->load(['pasien', 'pendaftaran']),
        ]);
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dihapus',
        ]);
    }

    public function byPatient($user_id)
    {
        $data = Pembayaran::whereHas('pasien', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->with(['pasien', 'pendaftaran'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function uploadBukti(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $validated['bukti_bayar'];
        $filename = 'bukti_bayar/' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        Storage::disk('public')->put($filename, file_get_contents($file));

        $pembayaran->bukti_bayar = $filename;
        $pembayaran->save();

        return response()->json([
            'success' => true,
            'message' => 'Bukti bayar berhasil diupload',
            'data' => $pembayaran,
        ]);
    }
}
