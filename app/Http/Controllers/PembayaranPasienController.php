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
         * SIMPAN KE PRIVATE (ANTI 403)
         * lokasi fisik: storage/app/private/bukti_pembayaran/xxxxx.png
         * catatan: ini bukan public, jadi gak bisa diakses langsung dari URL
         */
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'private');

        $pembayaran->update([
            'bukti_pembayaran' => $path, // contoh: bukti_pembayaran/xxx.png
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

        // kandidat path yang mungkin (private & public legacy)
        $candidatesPrivate = [];
        $candidatesPublic  = [];

        // buang domain kalau tersimpan full url
        $raw = preg_replace('#^https?://[^/]+/#', '', $raw);
        $raw = ltrim($raw, '/');

        // 1) kalau sudah path
        $candidatesPrivate[] = $raw;
        $candidatesPublic[]  = $raw;

        // 2) kalau cuma nama file
        if (!str_contains($raw, '/')) {
            // private standar baru
            $candidatesPrivate[] = 'bukti_pembayaran/' . $raw;

            // public legacy
            $candidatesPublic[]  = 'uploads/bukti/' . $raw;
            $candidatesPublic[]  = 'uploads/bukti_pembayaran/' . $raw;
        }

        // 3) normalisasi prefix legacy
        $clean = $raw;
        $clean = preg_replace('#^storage/#', '', $clean);
        $clean = preg_replace('#^public/#', '', $clean);
        $clean = preg_replace('#^bukti_pembayaran/#', 'bukti_pembayaran/', $clean);
        $clean = preg_replace('#^uploads/bukti_pembayaran/#', 'uploads/bukti/', $clean);
        $clean = ltrim($clean, '/');

        $candidatesPrivate[] = $clean;
        $candidatesPublic[]  = $clean;

        // dedupe
        $candidatesPrivate = array_values(array_unique(array_filter($candidatesPrivate)));
        $candidatesPublic  = array_values(array_unique(array_filter($candidatesPublic)));

        // CARI DI DISK PRIVATE DULU (yang baru)
        foreach ($candidatesPrivate as $path) {
            if (Storage::disk('private')->exists($path)) {
                $mime = Storage::disk('private')->mimeType($path) ?: 'application/octet-stream';

                return Storage::disk('private')->response($path, null, [
                    'Content-Type'  => $mime,
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma'        => 'no-cache',
                    'Expires'       => '0',
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            }
        }

        // FALLBACK: CARI DI PUBLIC (legacy upload lama)
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
