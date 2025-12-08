@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Rekam Medis Saya</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Dokter</th>
                    <th class="py-3 px-4 text-left">Diagnosa</th>
                    <th class="py-3 px-4 text-left">Tindakan</th>
                    <th class="py-3 px-4 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekamMedisList as $rekam)
                    <tr class="border-b hover:bg-gray-100">
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
