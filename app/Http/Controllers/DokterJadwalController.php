<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalDokter;
use Illuminate\Support\Facades\Auth;

class DokterJadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalDokter::where('dokter_id', Auth::id())->get();
        return view('dokter.manajemen_jadwal', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        JadwalDokter::create([
            'dokter_id' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('dokter.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        if ($jadwal->dokter_id != Auth::id()) {
            abort(403);
        }

        $jadwal->delete();

        return redirect()->route('dokter.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    //menampilkan data di pasien 
    // Tambahkan method ini di DokterJadwalController
public function pasienView()
{
    $jadwals = JadwalDokter::with('dokter')->get(); // pastikan relasi 'dokter' ada
    return view('pasien.jadwal_dokter', compact('jadwals'));
}


}