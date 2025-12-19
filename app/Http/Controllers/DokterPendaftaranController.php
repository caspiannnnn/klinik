<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DokterPendaftaranController extends Controller
{
    public function index()
    {
        $dokter = Auth::user();

        // ✅ Dokter hanya melihat pendaftaran yang memang dituju ke dokter tersebut
        // (dokter_id = dokter yang dipilih pasien saat daftar) atau yang diterima oleh dokter tersebut.
        $pendaftars = Pendaftaran::with(['user', 'dokter', 'diterimaOlehDokter'])
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokter.pendaftar', compact('pendaftars'));
    }

    public function show($id)
    {
        $dokter = Auth::user();

        $pendaftar = Pendaftaran::with(['user', 'dokter', 'diterimaOlehDokter'])
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->findOrFail($id);

        return view('dokter.pendaftaran.show', compact('pendaftar'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu_konfirmasi,diterima,ditolak',
        ]);

        $dokter = Auth::user();

        $p = Pendaftaran::where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->findOrFail($id);

        $old = (string) ($p->status ?? '');
        $new = (string) $request->status;

        if ($new === 'diterima') {
            $p->diterima_oleh_dokter_id = $dokter->id;

            // ✅ kalau belum ada dokter_id, set dokter yang menerima
            if (!$p->dokter_id) {
                $p->dokter_id = $dokter->id;
            }
        }

        $p->status = $new;
        $p->save();

        // notif ke pasien kalau status berubah
        if ($p->user_id && strtolower($old) !== strtolower($new)) {
            Notifikasi::create([
                'user_id' => $p->user_id,
                'judul'   => 'Status Pendaftaran',
                'pesan'   => 'Status pendaftaran Anda berubah dari "' . ($old ?: '-') . '" menjadi "' . $new . '".',
                'tipe'    => 'pendaftaran',
                // ✅ arahkan ke "Pendaftaran Saya" (bukan halaman notifikasi)
                'link'    => route('pendaftaran.saya'),
                'dibaca'  => false,
            ]);
        }

        return back();
    }

    public function rescheduleForm($id)
    {
        $dokter = Auth::user();

        $p = Pendaftaran::with(['user', 'dokter', 'diterimaOlehDokter'])
            ->where(function ($q) use ($dokter) {
                $q->where('dokter_id', $dokter->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokter->id);
            })
            ->findOrFail($id);

        // Dokter lain untuk spesialis yang sama (kalau mau pindah dokter)
        $dokters = User::query()
            ->where('role', 'dokter')
            ->where('spesialis', $p->spesialis)
            ->orderBy('name')
            ->get();

        return view('dokter.reschedule', compact('p', 'dokters'));
    }

    public function rescheduleSubmit(Request $request, $id)
    {
        $dokterLogin = Auth::user();

        $p = Pendaftaran::where(function ($q) use ($dokterLogin) {
                $q->where('dokter_id', $dokterLogin->id)
                  ->orWhere('diterima_oleh_dokter_id', $dokterLogin->id);
            })
            ->findOrFail($id);

        $request->validate([
            'tanggal_kunjungan' => 'required|date',
            'jam_kunjungan'     => 'required',
            'dokter_id'         => 'required|exists:users,id',
        ]);

        $dokter = User::query()->where('id', $request->dokter_id)->where('role', 'dokter')->first();
        if (!$dokter) {
            return back()->withErrors(['dokter_id' => 'Dokter tidak valid.'])->withInput();
        }

        // ✅ pastikan dokter tujuan masih spesialis yang sama
        if ((string) ($dokter->spesialis ?? '') !== (string) ($p->spesialis ?? '')) {
            return back()->withErrors(['dokter_id' => 'Dokter yang dipilih tidak sesuai spesialis pendaftaran.'])->withInput();
        }

        $same = ((string) $p->tanggal_kunjungan?->format('Y-m-d') === (string) $request->tanggal_kunjungan)
            && ((int) $p->dokter_id === (int) $dokter->id);

        if (!$same) {
            $count = Pendaftaran::countForDoctorOnDate((int) $dokter->id, (string) $request->tanggal_kunjungan);
            if ($count >= 5) {
                return back()->withErrors(['dokter_id' => 'Kuota dokter untuk tanggal tersebut sudah penuh (maksimal 5 pasien).'])->withInput();
            }
        }

        $updated = DB::transaction(function () use ($p, $request, $dokter) {
            [$nomor, $kode] = Pendaftaran::generateQueueForDoctorAndDate((int) $dokter->id, (string) $request->tanggal_kunjungan);

            $p->update([
                'dokter_id'         => $dokter->id,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'jam_kunjungan'     => $request->jam_kunjungan,
                'nomor_urut'        => $nomor,
                'kode_antrian'      => $kode,
                'status'            => 'menunggu_konfirmasi',
            ]);

            return $p->fresh();
        });

        // notif ke pasien
        if ($updated->user_id) {
            Notifikasi::create([
                'user_id' => $updated->user_id,
                'judul'   => 'Reschedule Jadwal',
                'pesan'   => 'Jadwal Anda direschedule. Nomor antrian baru: ' . ($updated->kode_antrian ?? '-'),
                'tipe'    => 'pendaftaran',
                'link'    => route('pasien.notifikasi'),
                'dibaca'  => false,
            ]);
        }

        return redirect()->route('dokter.pendaftar')->with('success', 'Reschedule berhasil. Nomor antrian diperbarui.');
    }
}
