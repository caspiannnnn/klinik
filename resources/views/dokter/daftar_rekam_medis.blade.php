@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-blue-700">Daftar Rekam Medis</h1>

    @if($rekamMedisList->isEmpty())
        <p class="text-gray-600">Belum ada rekam medis yang diinput.</p>
    @else
        <div class="bg-white shadow rounded-lg p-6">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-blue-100 text-blue-800 text-center">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama Pasien</th>
                        <th class="px-4 py-2">Diagnosa</th>
                        <th class="px-4 py-2">Tindakan</th>
                        <th class="px-4 py-2">Catatan</th>
                        <th class="px-4 py-2">Tanggal Input</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-center">
                    @foreach ($rekamMedisList as $index => $rekam)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $rekam->pendaftaran->nama }}</td>
                            <td class="px-4 py-2">{{ $rekam->diagnosa }}</td>
                            <td class="px-4 py-2">{{ $rekam->tindakan }}</td>
                            <td class="px-4 py-2">{{ $rekam->catatan ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $rekam->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
