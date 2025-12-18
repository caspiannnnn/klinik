@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-3 py-3">
    <div class="w-full max-w-5xl">
        <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-500">
                <h1 class="text-lg font-bold text-white">📝 Daftar Pasien Baru</h1>
                <p class="text-blue-50 text-xs">Input data pasien dan cetak kartu pasien.</p>
            </div>

            <div class="p-4">
                @if(session('success'))
                    <div class="mb-3 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-green-700 text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-700 text-xs">
                        <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('resepsionis.daftar.store') }}" method="POST" target="_blank" class="space-y-2">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        {{-- Nama --}}
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full rounded-lg border @error('name') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="Contoh: Ahmad Rizki" required>
                            @error('name') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Username --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                   class="w-full rounded-lg border @error('username') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="contoh: ahmadrizki" required>
                            @error('username') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full rounded-lg border @error('email') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="nama@email.com" required>
                            @error('email') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Password</label>
                            <div class="relative">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    value="{{ old('password') }}"
                                    class="w-full rounded-lg border @error('password') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Minimal 6 karakter"
                                >
                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-600 hover:text-blue-600"
                                    aria-label="Tampilkan password"
                                >
                                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10.733 5.076A10.744 10.744 0 0 1 12 5c7 0 10 7 10 7a18.4 18.4 0 0 1-1.67 2.68"/>
                                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                        <path d="M14.12 14.12a3 3 0 0 1-4.24-4.24"/>
                                        <path d="M1 1l22 22"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Alamat</label>
                            <input type="text" name="alamat" value="{{ old('alamat') }}"
                                   class="w-full rounded-lg border @error('alamat') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="Contoh: Jl. Merdeka No. 10" required>
                            @error('alamat') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">No HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                   class="w-full rounded-lg border @error('no_hp') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="08xxxxxxxxxx" required>
                            @error('no_hp') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- NIK --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">NIK</label>
                            <input type="text" name="nik" value="{{ old('nik') }}"
                                   class="w-full rounded-lg border @error('nik') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="16 digit" required>
                            @error('nik') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                   class="w-full rounded-lg border @error('tanggal_lahir') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   required>
                            @error('tanggal_lahir') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                    class="w-full rounded-lg border @error('jenis_kelamin') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    required>
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Keluhan --}}
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Keluhan</label>
                            <textarea name="keluhan" rows="2"
                                      class="w-full rounded-lg border @error('keluhan') border-red-400 @else border-gray-300 @enderror px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                      placeholder="Tuliskan keluhan pasien..." required>{{ old('keluhan') }}</textarea>
                            @error('keluhan') <p class="text-[11px] text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-1">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition">
                            Simpan & Cetak Kartu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const input = document.getElementById('password');
        const btn = document.getElementById('togglePassword');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (!input || !btn || !eyeOpen || !eyeClosed) return;

        btn.addEventListener('click', function () {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            eyeOpen.classList.toggle('hidden', !isHidden);
            eyeClosed.classList.toggle('hidden', isHidden);

            btn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    })();
</script>
@endsection
