<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminPembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    /**
     * ✅ Form buat tagihan baru (admin)
     * Pasien hanya muncul jika sudah ada rekam medis dari dokter (diagnosa & tindakan terisi).
     */
    public function create()
    {
        // ✅ Ambil user_id pasien yang sudah punya rekam medis valid
        // Sumber: rekam_medis -> pendaftarans -> user_id
        $eligibleUserIds = DB::table('rekam_medis')
            ->join('pendaftarans', 'rekam_medis.pendaftaran_id', '=', 'pendaftarans.id')
            ->whereNotNull('rekam_medis.diagnosa')
            ->where('rekam_medis.diagnosa', '!=', '')
            ->whereNotNull('rekam_medis.tindakan')
            ->where('rekam_medis.tindakan', '!=', '')
            ->select('pendaftarans.user_id')
            ->distinct();

        // ✅ Ambil pasien yang eligible
        $pasiens = User::where('role', 'pasien')
            ->whereIn('id', $eligibleUserIds)
            ->orderBy('name', 'asc')
            ->get();

        // ✅ Preview kode tagihan: INV-YYYYMMDD-0001 (urut harian)
        $datePart = now()->format('Ymd');
        $prefix = "INV-{$datePart}-";

        $last = Pembayaran::where('kode_tagihan', 'like', $prefix . '%')
            ->orderBy('kode_tagihan', 'desc')
            ->value('kode_tagihan');

        $nextNumber = 1;
        if ($last) {
            $lastNumber = (int) substr($last, -4);
            $nextNumber = $lastNumber + 1;
        }

        $previewKode = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        return view('admin.pembayaran.create', compact('pasiens', 'previewKode'));
    }

    /**
     * ✅ Simpan tagihan baru (admin)
     * - kode_tagihan auto-generate
     * - status default otomatis
     * - ✅ validasi: user_id harus pasien yang sudah punya rekam medis valid
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $isEligible = DB::table('rekam_medis')
                        ->join('pendaftarans', 'rekam_medis.pendaftaran_id', '=', 'pendaftarans.id')
                        ->where('pendaftarans.user_id', $value)
                        ->whereNotNull('rekam_medis.diagnosa')
                        ->where('rekam_medis.diagnosa', '!=', '')
                        ->whereNotNull('rekam_medis.tindakan')
                        ->where('rekam_medis.tindakan', '!=', '')
                        ->exists();

                    if (!$isEligible) {
                        $fail('Pasien belum memiliki rekam medis (diagnosa & tindakan) dari dokter, sehingga belum bisa dibuatkan tagihan.');
                    }
                }
            ],
            'jumlah' => 'required|numeric|min:0',
        ]);

        // ✅ Generate kode tagihan: INV-YYYYMMDD-0001 (urut harian)
        $datePart = now()->format('Ymd');
        $prefix = "INV-{$datePart}-";

        $last = Pembayaran::where('kode_tagihan', 'like', $prefix . '%')
            ->orderBy('kode_tagihan', 'desc')
            ->value('kode_tagihan');

        $nextNumber = 1;
        if ($last) {
            $lastNumber = (int) substr($last, -4);
            $nextNumber = $lastNumber + 1;
        }

        $kodeTagihan = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        Pembayaran::create([
            'user_id'          => $request->user_id,
            'kode_tagihan'     => $kodeTagihan,
            'jumlah'           => $request->jumlah,
            'status'           => 'belum dibayar', // ✅ default otomatis
            'bukti_pembayaran' => null,
        ]);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Tagihan berhasil dibuat.');
    }

    public function konfirmasi()
    {
        $pembayarans = Pembayaran::with('user')
            ->where('status', 'menunggu konfirmasi')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pembayaran.konfirmasi', compact('pembayarans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function bukti($id)
    {
        $pembayaran = Pembayaran::with('user')->findOrFail($id);

        if (empty($pembayaran->bukti_pembayaran)) {
            abort(404);
        }

        $raw = $pembayaran->bukti_pembayaran;

        $candidates = [];
        $candidates[] = ltrim($raw, '/');

        if (!str_contains($raw, '/')) {
            $candidates[] = 'uploads/bukti/' . $raw;
            $candidates[] = 'uploads/bukti_pembayaran/' . $raw;
        }

        $clean = $raw;
        $clean = preg_replace('#^storage/#', '', $clean);
        $clean = preg_replace('#^bukti_pembayaran/#', '', $clean);
        $clean = preg_replace('#^uploads/bukti_pembayaran/#', 'uploads/bukti/', $clean);
        $clean = ltrim($clean, '/');
        $candidates[] = $clean;

        $candidates = array_values(array_unique($candidates));

        foreach ($candidates as $relativePath) {
            if (Storage::disk('public')->exists($relativePath)) {
                $fullPath = Storage::disk('public')->path($relativePath);

                return response()->file($fullPath, [
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma'        => 'no-cache',
                ]);
            }
        }

        abort(404);
    }
}
