@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold text-blue-700 mb-4">📝 Daftar Pasien Baru</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('resepsionis.daftar.store') }}" method="POST" target="_blank">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        {{-- Password + Eye Toggle --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Password</label>

            <div class="relative">
                <input
                    id="password"
                    type="password"
                    name="password"
                    value="{{ old('password') }}"
                    class="w-full border px-3 py-2 rounded pr-10"
                    required
                    autocomplete="new-password"
                >

                <button
                    type="button"
                    id="togglePassword"
                    class="absolute inset-y-0 right-0 flex items-center px-3"
                    aria-label="Tampilkan password"
                >
                    {{-- eye icon (inline svg) --}}
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>

                    {{-- eye-off icon (inline svg) --}}
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.733 5.076A10.744 10.744 0 0 1 12 5c7 0 10 7 10 7a18.4 18.4 0 0 1-1.67 2.68"/>
                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                        <path d="M14.12 14.12a3 3 0 0 1-4.24-4.24"/>
                        <path d="M1 1l22 22"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Alamat</label>
            <input type="text" name="alamat" value="{{ old('alamat') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">No HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">NIK</label>
            <input type="text" name="nik" value="{{ old('nik') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="w-full border px-3 py-2 rounded" required>
                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih --</option>
                <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Keluhan</label>
            <textarea name="keluhan" class="w-full border px-3 py-2 rounded" rows="3" required>{{ old('keluhan') }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan & Cetak Kartu
        </button>
    </form>
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
