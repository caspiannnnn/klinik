@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-blue-700">Daftar Pendaftaran Pasien</h1>

    @if($pendaftarans->isEmpty())
        <p class="text-gray-600">Belum ada data pendaftaran.</p>
    @else
    <table class="min-w-full bg-white shadow rounded">
        <thead class="bg-blue-600 text-white text-center">
            <tr>
                <th class="py-3 px-4">#</th>
                <th class="py-3 px-4">Nama Pasien</th>
                <th class="py-3 px-4">Tanggal Daftar</th>
                <th class="py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendaftarans as $pendaftaran)
                <tr class="border-b hover:bg-gray-100 text-center">
                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                    <td class="py-2 px-4">{{ $pendaftaran->nama ?? optional($pendaftaran->user)->name }}</td>
                    <td class="py-2 px-4">{{ optional($pendaftaran->created_at)->format('d M Y H:i') }}</td>
                    <td class="py-2 px-4">
                        <a href="{{ route('resepsionis.pendaftaran.cetak', $pendaftaran->id) }}"
                           target="_blank"
                           class="text-blue-600 hover:underline">
                           Cetak Kartu
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
