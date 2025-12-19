@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-extrabold text-blue-800 mb-6 text-center">🔁 Reschedule Jadwal</h1>

    <div class="bg-white shadow-xl rounded-lg p-8 border border-gray-200">
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
                <div><span class="font-semibold">Nama:</span> {{ $p->nama }}</div>
                <div><span class="font-semibold">Spesialis:</span> {{ $p->spesialis ?? '-' }}</div>
                <div><span class="font-semibold">Dokter:</span> {{ optional($p->dokter)->name ?? '-' }}</div>
                <div><span class="font-semibold">Tanggal/Jam:</span> {{ optional($p->tanggal_kunjungan)->format('Y-m-d') ?? '-' }} {{ $p->jam_kunjungan ?? '' }}</div>
                <div><span class="font-semibold">Nomor Antrian:</span> {{ $p->kode_antrian ?? '-' }}</div>
            </div>
        </div>

        <form action="{{ route('pendaftaran.reschedule.submit', $p->id) }}" method="POST" class="space-y-6">
            @csrf

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

                <div>
                    <label for="spesialis" class="block text-sm font-semibold text-gray-700 mb-1">Spesialis Dokter</label>
                    <select
                        id="spesialis"
                        name="spesialis"
                        class="w-full border @error('spesialis') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                        required>
                        <option value="" disabled>-- Pilih Spesialis --</option>
                        @foreach (($spesialisList ?? collect()) as $sp)
                            <option value="{{ $sp }}" {{ (string) old('spesialis', $p->spesialis) === (string) $sp ? 'selected' : '' }}>{{ $sp }}</option>
                        @endforeach
                    </select>
                    @error('spesialis')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="dokter_id" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Dokter</label>
                    <select
                        id="dokter_id"
                        name="dokter_id"
                        class="w-full border @error('dokter_id') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                        required>
                        <option value="" disabled>-- Pilih Dokter --</option>
                        @foreach (($dokters ?? collect()) as $d)
                            <option value="{{ $d->id }}" {{ (string) old('dokter_id', $p->dokter_id) === (string) $d->id ? 'selected' : '' }}>
                                {{ $d->name }} ({{ $d->spesialis ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Nomor antrian akan otomatis diperbarui setelah reschedule.</p>
                    @error('dokter_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow transition duration-200">
                    Simpan Reschedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
