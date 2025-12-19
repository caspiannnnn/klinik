@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Dashboard Dokter</h1>
    <p class="text-lg mb-4">
        Selamat datang, Dokter {{ Auth::user()->name }} - Spesialis {{ Auth::user()->spesialis ? Auth::user()->spesialis : '-' }}!
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Total Pasien</h2>
            <p class="text-3xl font-bold text-blue-500">{{ $totalPasien }}</p>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Jadwal Hari Ini</h2>
            <p class="text-3xl font-bold text-blue-500">{{ $totalJadwal }}</p>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Total Konsultasi</h2>
            <p class="text-3xl font-bold text-blue-500">{{ $totalKonsultasi }}</p>
        </div>
    </div>

    {{-- ✅ 1 Tabel: Minggu Ini + Minggu Depan (Minggu Ini di atas) --}}
    <h2 class="text-2xl font-bold mt-8 mb-4 text-blue-600">Jadwal Konsultasi (Minggu Ini & Minggu Depan)</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-blue-500 text-white text-center">
                    <th class="py-3 px-4">Periode</th>
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Pasien</th>
                    <th class="py-3 px-4">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // ✅ Urutkan masing-masing periode, tapi tampilkan Minggu Ini dulu baru Minggu Depan
                    $mingguIniSorted = collect($jadwalMingguIni ?? collect())->sortBy(function ($i) {
                        $tgl = (string) ($i->tanggal_kunjungan ?? '');
                        $jam = (string) ($i->jam_kunjungan ?? '');
                        return $tgl . ' ' . $jam;
                    })->values();

                    $mingguDepanSorted = collect($jadwalMingguDepan ?? collect())->sortBy(function ($i) {
                        $tgl = (string) ($i->tanggal_kunjungan ?? '');
                        $jam = (string) ($i->jam_kunjungan ?? '');
                        return $tgl . ' ' . $jam;
                    })->values();

                    $gabunganJadwal = collect();

                    foreach ($mingguIniSorted as $x) {
                        $gabunganJadwal->push(['periode' => 'Minggu Ini', 'item' => $x]);
                    }
                    foreach ($mingguDepanSorted as $x) {
                        $gabunganJadwal->push(['periode' => 'Minggu Depan', 'item' => $x]);
                    }
                @endphp

                @forelse($gabunganJadwal as $row)
                    @php
                        $item = $row['item'];
                        $periode = $row['periode'];

                        $tgl = $item->tanggal_kunjungan ?? null;
                        $jam = $item->jam_kunjungan ?? null;

                        $tglView = $tgl ? \Carbon\Carbon::parse($tgl)->format('d-m-Y') : '-';
                        $jamView = $jam ? substr((string) $jam, 0, 5) : '-';
                    @endphp

                    <tr class="border-b hover:bg-gray-100 text-center">
                        <td class="py-3 px-4">{{ $periode }}</td>
                        <td class="py-3 px-4">{{ $tglView }}</td>
                        <td class="py-3 px-4">{{ $item->nama ?? optional($item->user)->name ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $jamView }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center">Tidak ada jadwal untuk minggu ini & minggu depan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
