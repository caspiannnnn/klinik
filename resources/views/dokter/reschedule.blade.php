@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-blue-700">Reschedule Jadwal Pasien</h1>

    <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <div class="font-semibold mb-1">Terjadi kesalahan pada input Anda:</div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <div class="text-sm text-gray-700">
                <div><span class="font-semibold">Nama Pasien:</span> {{ $p->nama }}</div>
                <div><span class="font-semibold">Spesialis:</span> {{ $p->spesialis ?? '-' }}</div>
                <div><span class="font-semibold">Dokter Saat Ini:</span> {{ optional($p->dokter)->name ?? '-' }}</div>
                <div><span class="font-semibold">Tanggal/Jam:</span> {{ optional($p->tanggal_kunjungan)->format('Y-m-d') ?? '-' }} {{ $p->jam_kunjungan ?? '' }}</div>
                <div><span class="font-semibold">Nomor Antrian:</span> {{ $p->kode_antrian ?? '-' }}</div>
            </div>
        </div>

        <form action="{{ route('dokter.pendaftaran.reschedule.submit', $p->id) }}" method="POST" class="space-y-6">
            @csrf

            {{-- ✅ dokter_id tetap dikirim, tapi tanpa dropdown --}}
            @php
                $auth = Auth::user();
                $autoDokterId = old('dokter_id', ($auth && (($auth->role ?? '') === 'dokter') ? $auth->id : $p->dokter_id));
            @endphp
            <input type="hidden" name="dokter_id" value="{{ $autoDokterId }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_kunjungan" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Kunjungan</label>
                    <input
                        type="date"
                        id="tanggal_kunjungan"
                        name="tanggal_kunjungan"
                        value="{{ old('tanggal_kunjungan', optional($p->tanggal_kunjungan)->format('Y-m-d')) }}"
                        class="w-full border @error('tanggal_kunjungan') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                        required>
                    @error('tanggal_kunjungan')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jam_kunjungan" class="block text-sm font-semibold text-gray-700 mb-1">Jam Kunjungan</label>
                    <input
                        type="time"
                        id="jam_kunjungan"
                        name="jam_kunjungan"
                        value="{{ old('jam_kunjungan', $p->jam_kunjungan) }}"
                        class="w-full border @error('jam_kunjungan') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                        required>
                    @error('jam_kunjungan')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="mt-1 text-xs text-gray-500">Nomor antrian akan otomatis berubah setelah reschedule.</p>

            <div class="pt-2 text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-200">
                    Simpan Reschedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
