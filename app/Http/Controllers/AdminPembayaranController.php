<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Notifikasi;
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
     * - ✅ kirim notifikasi ke pasien
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

        $pembayaran = Pembayaran::create([
            'user_id'          => $request->user_id,
            'kode_tagihan'     => $kodeTagihan,
            'jumlah'           => $request->jumlah,
            'status'           => 'belum dibayar',
            'bukti_pembayaran' => null,
        ]);

        // ✅ NOTIFIKASI: tagihan dibuat
        Notifikasi::create([
            'user_id' => $pembayaran->user_id,
            'judul'   => 'Tagihan Baru',
            'pesan'   => 'Tagihan baru telah dibuat dengan kode ' . $pembayaran->kode_tagihan .
                        ' sebesar Rp ' . number_format($pembayaran->jumlah, 0, ',', '.') .
                        '. Silakan cek menu Tagihan.',
            'tipe'    => 'pembayaran',
            'link'    => route('pasien.tagihan'),
            'dibaca'  => false,
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

    /**
     * ✅ Update status pembayaran (admin)
     * ✅ kirim notifikasi ketika status berubah (terutama lunas)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $oldStatus = (string) $pembayaran->status;
        $newStatus = (string) $request->status;

        $pembayaran->update([
            'status' => $newStatus,
        ]);

        // ✅ NOTIFIKASI: status berubah (hindari spam kalau status sama)
        if (strtolower(trim($oldStatus)) !== strtolower(trim($newStatus))) {
            $judul = 'Status Pembayaran Diperbarui';
            $pesan = 'Status tagihan ' . $pembayaran->kode_tagihan .
                     ' berubah dari "' . $oldStatus . '" menjadi "' . $newStatus . '".';

            // khusus lunas, kasih pesan lebih “berhasil”
            if (strtolower(trim($newStatus)) === 'lunas') {
                $judul = 'Pembayaran Lunas';
                $pesan = 'Pembayaran tagihan ' . $pembayaran->kode_tagihan .
                         ' telah dikonfirmasi LUNAS. Terima kasih.';
            }

            Notifikasi::create([
                'user_id' => $pembayaran->user_id,
                'judul'   => $judul,
                'pesan'   => $pesan,
                'tipe'    => 'pembayaran',
                'link'    => route('pasien.tagihan'),
                'dibaca'  => false,
            ]);
        }

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
