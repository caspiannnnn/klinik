@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-extrabold text-blue-800 mb-6 text-center">📝 Daftar Konsultasi Pasien</h1>

    <div class="bg-white shadow-xl rounded-lg p-8 border border-gray-200">
        {{-- Ringkasan error --}}
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

        @php
            $u = Auth::user();
            $prefNama = old('nama', $u->name ?? '');
            $prefTgl  = old('tanggal_lahir', optional($u->tanggal_lahir)->format('Y-m-d') ?? '');
            $prefJk   = old('jenis_kelamin', $u->jenis_kelamin ?? '');
            $prefHp   = old('no_hp', $u->no_hp ?? $u->telepon ?? '');
            $prefNik  = old('nik', $u->nik ?? '');
        @endphp

        <form action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>

                    <input type="hidden" name="nama" value="{{ $prefNama }}">
                    <input
                        type="text"
                        id="nama"
                        value="{{ $prefNama }}"
                        readonly
                        class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm"
                        placeholder="Contoh: Ahmad Rizki">
                    @error('nama')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>

                    <input type="hidden" name="tanggal_lahir" value="{{ $prefTgl }}">
                    <input
                        type="date"
                        id="tanggal_lahir"
                        value="{{ $prefTgl }}"
                        readonly
                        class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm">
                    @error('tanggal_lahir')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin</label>

                    <input type="hidden" name="jenis_kelamin" value="{{ $prefJk }}">
                    <select
                        id="jenis_kelamin"
                        disabled
                        class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm">
                        <option value="" disabled {{ $prefJk ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" {{ $prefJk === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $prefJk === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP</label>

                    <input type="hidden" name="no_hp" value="{{ $prefHp }}">
                    <input
                        type="text"
                        id="no_hp"
                        value="{{ $prefHp }}"
                        readonly
                        inputmode="numeric"
                        pattern="^[0-9]{9,15}$"
                        autocomplete="tel"
                        class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm"
                        placeholder="08xxxxxxxxxx">
                    @error('no_hp')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-sm font-semibold text-gray-700 mb-1">NIK</label>

                    <input type="hidden" name="nik" value="{{ $prefNik }}">
                    <input
                        type="text"
                        id="nik"
                        value="{{ $prefNik }}"
                        readonly
                        inputmode="numeric"
                        pattern="^[0-9]{16}$"
                        class="w-full border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:outline-none rounded-lg px-4 py-2 text-sm"
                        placeholder="16 digit NIK">
                    <p class="mt-1 text-xs text-gray-500">Gunakan angka saja, tepat 16 digit.</p>
                    @error('nik')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keluhan -->
                <div class="md:col-span-2">
                    <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-1">Keluhan</label>
                    <textarea
                        id="keluhan"
                        name="keluhan"
                        required
                        rows="4"
                        class="w-full border @error('keluhan') border-red-500 @else border-gray-300 @enderror focus:ring focus:ring-blue-200 focus:outline-none rounded-lg px-4 py-2 text-sm"
                        placeholder="Contoh: Sakit kepala sejak 2 hari...">{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow transition duration-200">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ✅ POPUP SUCCESS --}}
@if (session('success'))
<div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
        <h2 class="text-xl font-bold text-blue-800 mb-2">✅ Pendaftaran Berhasil!</h2>
        <p class="text-gray-700 mb-6">{{ session('success') }}</p>

        <div class="text-right">
            <button
                type="button"
                id="closeSuccessModal"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                OK
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('successModal');
        const btn = document.getElementById('closeSuccessModal');

        if (btn && modal) {
            btn.addEventListener('click', function () {
                modal.remove();
            });

            // klik area gelap untuk tutup
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });

            // esc untuk tutup
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    modal.remove();
                }
            });
        }
    })();
</script>
@endif

@endsection
