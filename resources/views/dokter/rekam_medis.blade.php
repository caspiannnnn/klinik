@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-blue-700 mb-6">Input Rekam Medis</h1>

    <div class="bg-white shadow rounded-lg p-6">
        {{-- Data Pasien --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Data Pasien</h2>
            <p><strong>Nama:</strong> {{ $pendaftaran->nama }}</p>
            <p><strong>Tanggal Lahir:</strong> {{ $pendaftaran->tanggal_lahir->format('Y-m-d') }}</p>
            <p><strong>Jenis Kelamin:</strong> {{ $pendaftaran->jenis_kelamin }}</p>
        </div>

        {{-- Form Rekam Medis --}}
        <form action="{{ route('dokter.rekam_medis.store', $pendaftaran->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium" for="diagnosa">Diagnosa</label>
                    <input id="diagnosa" name="diagnosa" type="text" class="w-full border-gray-300 rounded px-3 py-2" placeholder="Contoh: Demam tinggi" value="{{ old('diagnosa') }}">
                    @error('diagnosa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-1 font-medium" for="tindakan">Tindakan</label>
                    <input id="tindakan" name="tindakan" type="text" class="w-full border-gray-300 rounded px-3 py-2" placeholder="Contoh: Diberi obat penurun demam" value="{{ old('tindakan') }}">
                    @error('tindakan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-1 font-medium" for="catatan">Catatan Dokter</label>
                    <textarea id="catatan" name="catatan" rows="3" class="w-full border-gray-300 rounded px-3 py-2" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Simpan Rekam Medis
                </button>
                <a href="{{ url('/dokter/pasien') }}" class="ml-4 text-gray-600 hover:underline">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
