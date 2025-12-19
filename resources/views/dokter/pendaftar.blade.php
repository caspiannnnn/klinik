@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-blue-700">Daftar Pendaftar Konsultasi</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-blue-100 text-blue-800 text-center">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Pasien</th>
                    <th class="px-4 py-2">Tanggal Kunjungan</th>
                    <th class="px-4 py-2">Diterima Oleh</th>
                    <th class="px-4 py-2">No Antrian</th>
                    <th class="px-4 py-2">Keluhan</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Reschedule</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-center ">
                @forelse ($pendaftars as $index => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $p->nama }}</td>
                        <td class="px-4 py-2">
                            {{ optional($p->tanggal_kunjungan)->format('d-m-Y') ?? '-' }}
                            {{ $p->jam_kunjungan ? (' ' . substr((string)$p->jam_kunjungan, 0, 5)) : '' }}
                        </td>
                        <td class="px-4 py-2">{{ optional($p->diterimaOlehDokter)->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $p->kode_antrian ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $p->keluhan }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('dokter.pendaftaran.updateStatus', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                @php
                                    $status = $p->status ?? 'menunggu_konfirmasi';
                                @endphp

                                <select name="status" onchange="this.form.submit()" class="text-xs rounded-full px-2 py-1 bg-gray-100 text-gray-700 focus:outline-none">
                                    <option value="menunggu_konfirmasi" {{ $status == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu konfirmasi</option>
                                    <option value="diterima" {{ $status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('dokter.pendaftaran.reschedule.form', $p->id) }}" class="text-blue-600 hover:underline text-sm">Reschedule</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">Belum ada pendaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
