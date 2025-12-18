@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Jadwal Dokter</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($jadwals as $jadwal)
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">
                    {{ ($jadwal->dokter && ($jadwal->dokter->role ?? null) === 'dokter') ? $jadwal->dokter->name : 'Nama Dokter' }}
                </h2>
                <p class="text-gray-600">Spesialis: {{ ($jadwal->dokter && ($jadwal->dokter->role ?? null) === 'dokter') ? ($jadwal->dokter->spesialis ?? '-') : '-' }}</p>
                <p class="text-gray-600">Hari: {{ ucfirst($jadwal->hari) }}</p>
                <p class="text-gray-600">Waktu: {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
                {{-- <div class="mt-4">
                    <a href="{{ route('pasien.pendaftaran', ['dokter_id' => $jadwal->dokter_id]) }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Daftar
                    </a>
                </div> --}}
            </div>
        @empty
            <p class="text-gray-500">Belum ada jadwal dokter yang tersedia.</p>
        @endforelse
    </div>
@endsection
