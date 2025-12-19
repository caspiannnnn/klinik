@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-blue-700 mb-6">Rekam Medis Saya</h1>

        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-blue-600 text-white text-center">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Dokter</th>
                        <th class="py-3 px-4">Diagnosa</th>
                        <th class="py-3 px-4">Tindakan</th>
                        <th class="py-3 px-4">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekamMedisList as $rekam)
                        <tr class="border-b hover:bg-gray-50 text-center">
                            <td class="py-3 px-4">{{ $rekam->created_at->format('d-m-Y') }}</td>
                            <td class="py-3 px-4">{{ $rekam->dokter->name ?? 'Tidak diketahui' }}</td>
                            <td class="py-3 px-4">{{ $rekam->diagnosa }}</td>
                            <td class="py-3 px-4">{{ $rekam->tindakan }}</td>
                            <td class="py-3 px-4">{{ $rekam->catatan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Belum ada rekam medis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
