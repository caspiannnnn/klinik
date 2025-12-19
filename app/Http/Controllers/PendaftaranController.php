<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\JadwalDokter;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        $selectedTanggal = (string) $request->query('tanggal_kunjungan', '');
        $selectedSpesialis = (string) $request->query('spesialis', '');

        $spesialisList = User::query()
            ->where('role', 'dokter')
            ->whereNotNull('spesialis')
            ->where('spesialis', '!=', '')
            ->select('spesialis')
            ->distinct()
            ->orderBy('spesialis')
            ->pluck('spesialis');

        $dokters = collect();
        $jadwalsForTanggal = collect();

        if ($selectedTanggal !== '' && $selectedSpesialis !== '') {
            $hari = \Carbon\Carbon::parse($selectedTanggal)->locale('id')->translatedFormat('l');

            $candidateDokters = User::query()
                ->where('role', 'dokter')
                ->where('spesialis', $selectedSpesialis)
                ->orderBy('name')
                ->get();

            // Ambil jadwal dokter pada hari tsb
            $jadwalsForTanggal = JadwalDokter::query()
                ->whereIn('dokter_id', $candidateDokters->pluck('id'))
                ->where('hari', $hari)
                ->get();

            // Filter dokter yang kuotanya masih tersedia (maks 5 pasien/dokter/tanggal)
            $dokters = $candidateDokters->filter(function ($dokter) use ($selectedTanggal) {
                $count = Pendaftaran::countForDoctorOnDate((int) $dokter->id, (string) $selectedTanggal);
                return $count < 5;
            })->values();
        }

        $view = view()->exists('pasien.daftar') ? 'pasien.daftar' : 'pasien.pendaftaran';
        return view($view, [
            'selectedTanggal' => $selectedTanggal,
            'selectedSpesialis' => $selectedSpesialis,
            'spesialisList' => $spesialisList,
            'dokters' => $dokters,
            'jadwalsForTanggal' => $jadwalsForTanggal,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'             => 'required|string|max:255',
            'tanggal_lahir'    => 'required|date',
            'jenis_kelamin'    => 'required|string|max:50',
            'no_hp'            => 'required|string|max:20',
            'nik'              => 'required|string|max:20',
            'alamat'           => 'required|string|max:255',
            'keluhan'          => 'required|string|max:255',
            'tanggal_kunjungan'=> 'required|date',
            'spesialis'        => 'required|string|max:255',
            'dokter_id'        => 'required|exists:users,id',
            'jam_kunjungan'    => 'required',
        ]);

        $dokter = User::query()
            ->where('id', $request->dokter_id)
            ->where('role', 'dokter')
            ->first();

        if (!$dokter) {
            return back()->withErrors(['dokter_id' => 'Dokter tidak valid.'])->withInput();
        }

        if ((string) ($dokter->spesialis ?? '') !== (string) $request->spesialis) {
            return back()->withErrors(['dokter_id' => 'Dokter yang dipilih tidak sesuai spesialis.'])->withInput();
        }

        $count = Pendaftaran::countForDoctorOnDate((int) $dokter->id, (string) $request->tanggal_kunjungan);
        if ($count >= 5) {
            return back()->withErrors(['dokter_id' => 'Kuota dokter untuk tanggal tersebut sudah penuh (maksimal 5 pasien). Silakan pilih dokter lain.'])->withInput();
        }

        $pendaftaran = DB::transaction(function () use ($request, $user, $dokter) {
            [$nomor, $kode] = Pendaftaran::generateQueueForDoctorAndDate((int) $dokter->id, (string) $request->tanggal_kunjungan);

            $p = Pendaftaran::create([
                'user_id'          => $user->id,
                'nama'             => $request->nama,
                'tanggal_lahir'    => $request->tanggal_lahir,
                'jenis_kelamin'    => $request->jenis_kelamin,
                'no_hp'            => $request->no_hp,
                'nik'              => $request->nik,
                'alamat'           => $request->alamat,
                'keluhan'          => $request->keluhan,
                'tanggal_kunjungan'=> $request->tanggal_kunjungan,
                'jam_kunjungan'    => $request->jam_kunjungan,
                'spesialis'        => $request->spesialis,
                'dokter_id'        => $dokter->id,
                'nomor_urut'       => $nomor,
                'kode_antrian'     => $kode,
                'status'           => 'menunggu_konfirmasi',
            ]);

            Notifikasi::create([
                'user_id' => $user->id,
                'judul'   => 'Pendaftaran Berhasil',
                'pesan'   => 'Pendaftaran berhasil dibuat. Nomor antrian Anda: ' . ($p->kode_antrian ?? '-') . '.',
                'tipe'    => 'pendaftaran',
                'link'    => route('pendaftaran.saya'),
                'dibaca'  => false,
            ]);

            return $p;
        });

        return redirect()->route('pendaftaran.create')
            ->with('success', 'Pendaftaran berhasil dibuat.')
            ->with('antrian', $pendaftaran->kode_antrian);
    }

    public function myRegistrations()
    {
        $user = Auth::user();

        $pendaftarans = Pendaftaran::with('dokter')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pasien.pendaftaran_saya', compact('pendaftarans'));
    }

    public function success($id)
    {
        $user = Auth::user();

        $pendaftaran = Pendaftaran::query()
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('pasien.pendaftaran_sukses', compact('pendaftaran'));
    }

    public function checkin($token)
    {
        // (biarkan sesuai versi kamu)
        abort(404);
    }

    public function rescheduleForm($id)
    {
        $user = Auth::user();

        $p = Pendaftaran::with('dokter')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $spesialisList = User::query()
            ->where('role', 'dokter')
            ->whereNotNull('spesialis')
            ->where('spesialis', '!=', '')
            ->select('spesialis')
            ->distinct()
            ->orderBy('spesialis')
            ->pluck('spesialis');

        $dokters = User::query()
            ->where('role', 'dokter')
            ->where('spesialis', $p->spesialis)
            ->orderBy('name')
            ->get();

        return view('pasien.reschedule', compact('p', 'spesialisList', 'dokters'));
    }

    public function rescheduleSubmit(Request $request, $id)
    {
        $user = Auth::user();

        $p = Pendaftaran::query()
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'tanggal_kunjungan' => 'required|date',
            'jam_kunjungan'     => 'required',
            'spesialis'         => 'required|string|max:255',
            'dokter_id'         => 'required|exists:users,id',
        ]);

        $dokter = User::query()
            ->where('id', $request->dokter_id)
            ->where('role', 'dokter')
            ->first();

        if (!$dokter) {
            return back()->withErrors(['dokter_id' => 'Dokter tidak valid.'])->withInput();
        }

        if ((string) ($dokter->spesialis ?? '') !== (string) $request->spesialis) {
            return back()->withErrors(['dokter_id' => 'Dokter yang dipilih tidak sesuai spesialis.'])->withInput();
        }

        $same = ((string) optional($p->tanggal_kunjungan)->format('Y-m-d') === (string) $request->tanggal_kunjungan)
            && ((int) $p->dokter_id === (int) $dokter->id);

        if (!$same) {
            $count = Pendaftaran::countForDoctorOnDate((int) $dokter->id, (string) $request->tanggal_kunjungan);
            if ($count >= 5) {
                return back()->withErrors(['dokter_id' => 'Kuota dokter untuk tanggal tersebut sudah penuh (maksimal 5 pasien). Silakan pilih dokter lain.'])->withInput();
            }
        }

        $updated = DB::transaction(function () use ($p, $request, $dokter, $user) {
            [$nomor, $kode] = Pendaftaran::generateQueueForDoctorAndDate((int) $dokter->id, (string) $request->tanggal_kunjungan);

            $p->update([
                'dokter_id'         => $dokter->id,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'jam_kunjungan'     => $request->jam_kunjungan,
                'spesialis'         => $request->spesialis,
                'nomor_urut'        => $nomor,
                'kode_antrian'      => $kode,
                'status'            => 'menunggu_konfirmasi',
            ]);

            $fresh = $p->fresh();

            Notifikasi::create([
                'user_id' => $user->id,
                'judul'   => 'Reschedule Jadwal',
                'pesan'   => 'Jadwal berhasil direschedule. Nomor antrian baru Anda: ' . ($fresh->kode_antrian ?? '-') . '.',
                'tipe'    => 'pendaftaran',
                'link'    => route('pendaftaran.saya'),
                'dibaca'  => false,
            ]);

            return $fresh;
        });

        return redirect()->route('pendaftaran.create')
            ->with('success', 'Reschedule berhasil. Nomor antrian otomatis diperbarui.')
            ->with('antrian', $updated->kode_antrian);
    }

    // ✅ TAMBAHAN: hapus pendaftaran (hanya jika belum diterima)
    public function destroy($id)
    {
        $user = Auth::user();

        $p = Pendaftaran::query()
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // ✅ Jika sudah diterima dokter, tidak boleh dihapus
        if (strtolower((string) ($p->status ?? '')) === 'diterima') {
            return redirect()->route('pendaftaran.saya')
                ->with('error', 'Maaf pendaftaran tidak bisa dihapus, karena sudah diterima oleh dokter! Apabila ada perubahan jadwal silahkan pilih reschedule.');
        }

        $p->delete();

        return redirect()->route('pendaftaran.saya')
            ->with('success', 'Pendaftaran berhasil dihapus.');
    }
}
