@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Jadwal Dokter</h1>

    <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-blue-100 text-blue-800 text-center">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Dokter</th>
                    <th class="px-4 py-2">Spesialis</th>
                    <th class="px-4 py-2">Hari</th>
                    <th class="px-4 py-2">Jam Praktik</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-center">
                @forelse ($jadwals as $index => $jadwal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">
                            {{ ($jadwal->dokter && ($jadwal->dokter->role ?? null) === 'dokter') ? $jadwal->dokter->name : 'Nama Dokter' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ ($jadwal->dokter && ($jadwal->dokter->role ?? null) === 'dokter') ? ($jadwal->dokter->spesialis ?? '-') : '-' }}
                        </td>
                        <td class="px-4 py-2">{{ ucfirst($jadwal->hari) }}</td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada jadwal dokter yang tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
