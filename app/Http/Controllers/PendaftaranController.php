<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PendaftaranController extends Controller
{
    public function create()
    {
        return view('pasien.daftar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'no_hp'          => 'required|string|max:20',
            'nik'            => 'required|digits:16',
            'keluhan'        => 'required|string',
        ]);

        // Nomor urut harian (reset setiap hari)
        $today     = now()->toDateString();
        $last      = Pendaftaran::whereDate('created_at', $today)->max('nomor_urut');
        $nomorUrut = ($last ?? 0) + 1;

        // Bentuk kode antrian dan token QR
        $kodeAntrian = 'A' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
        $qrToken     = (string) Str::uuid();

        // Simpan pendaftaran
        $pendaftaran = Pendaftaran::create([
            'user_id'       => Auth::id(),
            'nama'          => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp'         => $request->no_hp,
            'nik'           => $request->nik,
            'keluhan'       => $request->keluhan,
            'status'        => 'menunggu',
            'nomor_urut'    => $nomorUrut,
            'kode_antrian'  => $kodeAntrian,
            'qr_token'      => $qrToken,
        ]);

        // Data yang di-encode ke QR: URL check-in
        $qrDataUrl = route('pendaftaran.checkin', $qrToken);

        // Generate QR (SVG) dan simpan ke storage/public/qrcodes
        $svg  = QrCode::format('svg')->size(240)->margin(1)->generate($qrDataUrl);
        $path = "qrcodes/{$qrToken}.svg";
        Storage::disk('public')->put($path, $svg);

        // Simpan path file QR
        $pendaftaran->update(['qr_path' => $path]);

        return redirect()->route('pendaftaran.success', $pendaftaran->id);
    }

    public function success($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        return view('pendaftaran.sukses', compact('pendaftaran'));
    }

    public function checkin($token)
    {
        $p = Pendaftaran::where('qr_token', $token)->firstOrFail();

        if (is_null($p->checkin_at)) {
            $p->update([
                'status'     => 'datang',
                'checkin_at' => now(),
            ]);
        }

        return view('pendaftaran.checkin', ['pendaftaran' => $p]);
    }
}
