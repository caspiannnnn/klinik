@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold text-blue-800 mb-6 text-center">✏️ Edit Dokter</h1>

    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
        <form action="{{ route('admin.dokter.update', $dokter->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input
                    type="text"
                    name="nama"
                    id="nama"
                    required
                    value="{{ old('nama', $dokter->name) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    required
                    value="{{ old('username', $dokter->username) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    value="{{ old('email', $dokter->email) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label for="spesialis" class="block text-sm font-semibold text-gray-700 mb-1">Spesialis</label>
                <input
                    type="text"
                    name="spesialis"
                    id="spesialis"
                    required
                    value="{{ old('spesialis', $dokter->spesialis) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
            </div>

            {{-- Password + Eye Toggle (opsional saat edit) --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>

                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="new-password"
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-12 focus:outline-none focus:ring focus:ring-blue-200"
                    >

                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3"
                        aria-label="Tampilkan password"
                    >
                        {{-- eye icon (open) --}}
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>

                        {{-- eye-off icon (closed) --}}
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.733 5.076A10.744 10.744 0 0 1 12 5c7 0 10 7 10 7a18.4 18.4 0 0 1-1.67 2.68"/>
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <path d="M14.12 14.12a3 3 0 0 1-4.24-4.24"/>
                            <path d="M1 1l22 22"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="pt-4 text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow transition">
                    Update Dokter
                </button>
            </div>
        </form>
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
