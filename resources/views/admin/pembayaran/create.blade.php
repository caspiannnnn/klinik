@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-blue-700 mb-6">➕ Buat Tagihan</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.pembayaran.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Pasien</label>

                <select name="user_id"
                        required
                        class="w-full border border-gray-300 rounded px-4 py-2"
                        {{ $pasiens->isEmpty() ? 'disabled' : '' }}>
                    <option value="">
                        {{ $pasiens->isEmpty() ? '-- Belum ada pasien yang sudah diperiksa dokter --' : '-- Pilih Pasien --' }}
                    </option>

                    @foreach($pasiens as $p)
                        <option value="{{ $p->id }}" {{ old('user_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->email }})
                        </option>
                    @endforeach
                </select>

                <p class="text-xs text-gray-500 mt-1">
                    Pasien hanya bisa dipilih jika sudah diperiksa dokter dan dokter sudah mengisi rekam medis (diagnosa & tindakan).
                </p>

                @error('user_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preview Kode Tagihan (auto) --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Kode Tagihan</label>
                <input type="text"
                       value="{{ $previewKode ?? '-' }}"
                       disabled
                       class="w-full border border-gray-300 rounded px-4 py-2 bg-gray-100 text-gray-700 cursor-not-allowed">
                <p class="text-xs text-gray-500 mt-1">Kode tagihan dibuat otomatis dan tidak bisa diubah.</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Jumlah (Rp)</label>
                <input type="number"
                       name="jumlah"
                       min="0"
                       required
                       value="{{ old('jumlah') }}"
                       class="w-full border border-gray-300 rounded px-4 py-2"
                       {{ $pasiens->isEmpty() ? 'disabled' : '' }}>
                @error('jumlah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
                    {{ $pasiens->isEmpty() ? 'disabled' : '' }}>
                Simpan Tagihan
            </button>
        </form>
    </div>
</div>
@endsection
