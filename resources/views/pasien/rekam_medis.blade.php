@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Rekam Medis Saya</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-blue-500 text-white text-center">
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Dokter</th>
                    <th class="py-3 px-4">Diagnosa</th>
                    <th class="py-3 px-4">Tindakan</th>
                    <th class="py-3 px-4">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekamMedisList as $rekam)
                    <tr class="border-b hover:bg-gray-100 text-center">
                        <td class="py-3 px-4">{{ $rekam->created_at->format('d-m-Y') }}</td>
                        <td class="py-3 px-4">{{ $rekam->dokter->name ?? 'Tidak diketahui' }}</td>
                        <td class="py-3 px-4">{{ $rekam->diagnosa }}</td>
                        <td class="py-3 px-4">{{ $rekam->tindakan }}</td>
                        <td class="py-3 px-4">{{ $rekam->catatan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada rekam medis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
