@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Dashboard Dokter</h1>

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

    <h2 class="text-2xl font-bold mt-8 mb-4 text-blue-600">Jadwal Konsultasi Hari Ini</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-blue-500 text-white text-center">
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Pasien</th>
                    <th class="py-3 px-4">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalHariIni as $item)
                    <tr class="border-b hover:bg-gray-100 text-center">
                        <td class="py-3 px-4">{{ optional($item->created_at)->format('d-m-Y') }}</td>
                        <td class="py-3 px-4">{{ $item->nama ?? optional($item->user)->name ?? '-' }}</td>
                        <td class="py-3 px-4">N/A</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center">Tidak ada jadwal hari ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
