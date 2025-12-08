@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-blue-700">Daftar Pendaftar Konsultasi</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-blue-100 text-blue-800">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Nama Pasien</th>
                    <th class="px-4 py-2 text-left">Tanggal Daftar</th>
                    <th class="px-4 py-2 text-left">Keluhan</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($pendaftars as $index => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $p->nama }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($p->created_at)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2">{{ $p->keluhan }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('dokter.pendaftaran.updateStatus', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs rounded-full px-2 py-1 bg-gray-100 text-gray-700 focus:outline-none">
                                    <option value="Menunggu" {{ $p->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Diterima" {{ $p->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Ditolak" {{ $p->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('resepsionis.pendaftaran.index') }}" class="text-blue-600 hover:underline text-sm">Lihat Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada pendaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
