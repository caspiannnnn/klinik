<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranPasienController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pasien.tagihan', compact('pembayarans'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pembayaran = Pembayaran::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        /**
         * SIMPAN KE PRIVATE DI DISK LOCAL (ANTI 403)
         * lokasi fisik: storage/app/private/bukti_pembayaran/xxxxx.png
         * catatan: ini bukan public, jadi gak bisa diakses langsung dari URL
         */
        $path = $request->file('bukti_pembayaran')->store('private/bukti_pembayaran', 'local');

        $pembayaran->update([
            'bukti_pembayaran' => $path, // contoh: private/bukti_pembayaran/xxx.png
            'status' => 'menunggu konfirmasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }

    /**
     * STREAM BUKTI VIA LARAVEL (ANTI 403)
     * - pasien: hanya miliknya
     * - admin: boleh semua
     * - support data lama (public/uploads/bukti atau filename saja)
     */
    public function bukti($id)
    {
        $user = Auth::user();

        $q = Pembayaran::query()->where('id', $id);

        // kalau bukan admin -> batasi milik sendiri
        if (!$user || (($user->role ?? null) !== 'admin')) {
            $q->where('user_id', Auth::id());
        }

        $pembayaran = $q->firstOrFail();

        if (empty($pembayaran->bukti_pembayaran)) {
            abort(404);
        }

        $raw = trim((string) $pembayaran->bukti_pembayaran);

        // buang domain kalau tersimpan full url
        $raw = preg_replace('#^https?://[^/]+/#', '', $raw);
        $raw = ltrim($raw, '/');

        $candidatesLocal = [];
        $candidatesPublic = [];

        // 1) path apa adanya
        $candidatesLocal[] = $raw;
        $candidatesPublic[] = $raw;

        // 2) kalau cuma nama file
        if (!str_contains($raw, '/')) {
            $candidatesLocal[]  = 'private/bukti_pembayaran/' . $raw;   // format baru fix
            $candidatesPublic[] = 'uploads/bukti/' . $raw;              // legacy
            $candidatesPublic[] = 'uploads/bukti_pembayaran/' . $raw;   // legacy lain
        }

        // 3) normalisasi: kalau data lama tersimpan tanpa "private/"
        if (str_starts_with($raw, 'bukti_pembayaran/')) {
            $candidatesLocal[] = 'private/' . $raw; // private/bukti_pembayaran/xxx.png
        }

        // dedupe
        $candidatesLocal = array_values(array_unique(array_filter($candidatesLocal)));
        $candidatesPublic = array_values(array_unique(array_filter($candidatesPublic)));

        // ✅ CARI DI DISK LOCAL (storage/app/...) DULU
        foreach ($candidatesLocal as $path) {
            if (Storage::disk('local')->exists($path)) {
                $mime = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';

                return Storage::disk('local')->response($path, null, [
                    'Content-Type'  => $mime,
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma'        => 'no-cache',
                    'Expires'       => '0',
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            }
        }

        // FALLBACK: CARI DI PUBLIC (legacy)
        foreach ($candidatesPublic as $path) {
            if (Storage::disk('public')->exists($path)) {
                $mime = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';

                return Storage::disk('public')->response($path, null, [
                    'Content-Type'  => $mime,
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma'        => 'no-cache',
                    'Expires'       => '0',
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            }
        }

        abort(404);
    }
}
