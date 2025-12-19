@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-blue-700 mb-6">Data Pasien & Input Rekam Medis</h1>

    {{-- Tabel Data Pasien --}}
    <div class="bg-white shadow rounded-lg p-6 mb-10">
        <h2 class="text-lg font-semibold mb-4">Daftar Pasien (Status: Diterima)</h2>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-blue-100 text-blue-800 text-center">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Pasien</th>
                    <th class="px-4 py-2">Tanggal Lahir</th>
                    <th class="px-4 py-2">Jenis Kelamin</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-center">
                @foreach($pendaftars as $index => $pendaftar)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $pendaftar->nama }}</td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y') }}
                        </td>
                        <td class="px-4 py-2">{{ $pendaftar->jenis_kelamin }}</td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('dokter.rekam_medis.form', $pendaftar->id) }}" class="text-blue-600 hover:underline text-sm">
                                Isi Rekam Medis
                            </a>
                        </td>
                    </tr>
                @endforeach
                @if($pendaftars->isEmpty())
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada pasien dengan status Diterima.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
