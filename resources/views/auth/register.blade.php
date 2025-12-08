<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        .font-modify { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-blue-50 flex items-center justify-center h-screen overflow-hidden font-modify">

    <form action="{{ route('register') }}" method="POST"
          class="bg-white border border-blue-200 p-6 rounded-2xl shadow-lg w-96 md:w-[34rem]">
        @csrf

        <!-- Logo -->
        <div class="flex justify-center mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14">
        </div>

        <h1 class="text-xl text-blue-600 font-bold mb-4 text-center">Register Pasien</h1>

        {{-- Grid 2 kolom (rapat) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <!-- Nama -->
            <div>
                <label class="block mb-1 text-gray-700 font-medium text-sm">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Username -->
            <div>
                <label class="block mb-1 text-gray-700 font-medium text-sm">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                       class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-1 text-gray-700 font-medium text-sm">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label class="block mb-1 text-gray-700 font-medium text-sm">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                       max="{{ now()->toDateString() }}"
                       class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <label class="block mb-1 text-gray-700 font-medium text-sm">Jenis Kelamin</label>
                <select name="jenis_kelamin" required
                        class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <!-- No HP -->
            <div>
                <label class="block mb-1 text-gray-700 font-medium text-sm">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                       inputmode="numeric" pattern="^[0-9]{9,15}$"
                       class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="08xxxxxxxxxx">
            </div>

            <!-- NIK -->
            <div>
                <label class="block mb-1 text-gray-700 font-medium text-sm">NIK</label>
                <input type="text" name="nik" value="{{ old('nik') }}" required
                       inputmode="numeric" pattern="^[0-9]{16}$"
                       class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="16 digit NIK">
            </div>

            <!-- Alamat (full width biar rapi) -->
            <div class="md:col-span-2">
                <label class="block mb-1 text-gray-700 font-medium text-sm">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat') }}" required
                       class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Contoh: Jl. Merdeka No. 10, Jakarta">
            </div>

            <!-- Password (full width biar rapi) -->
            <div class="md:col-span-2">
                <label class="block mb-1 text-gray-700 font-medium text-sm">Password</label>

                <div class="relative">
                    <input id="password" type="password" name="password" required
                           autocomplete="new-password"
                           class="w-full px-3 py-2 pr-11 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <!-- Toggle button -->
                    <button type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-blue-600 focus:outline-none"
                            aria-label="Tampilkan password">
                        <!-- Eye (show) -->
                        <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <!-- Eye off (hide) -->
                        <svg id="iconEyeOff" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3l18 18" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.477 10.477A3 3 0 0012 15a3 3 0 002.523-4.523" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7.362 7.561C5.68 8.68 4.407 10.218 3.458 12c1.274 4.057 5.065 7 9.542 7 1.728 0 3.356-.438 4.767-1.204" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M14.12 14.12L9.88 9.88" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.64 17.64C19.32 16.52 20.593 14.982 21.542 13.2c-1.274-4.057-5.065-7-9.542-7-1.43 0-2.805.3-4.055.844" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Hidden Role -->
        <input type="hidden" name="role" value="pasien">

        <!-- Register Button -->
        <button type="submit"
                class="w-full mt-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-md transition duration-300">
            Register
        </button>

        <!-- Login Link -->
        <p class="mt-4 text-center text-sm text-gray-600">
            Sudah punya akun?
            <a href="/login" class="text-blue-500 hover:underline font-medium">Sign in</a>
        </p>
    </form>

    <script>
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const iconEye = document.getElementById('iconEye');
        const iconEyeOff = document.getElementById('iconEyeOff');

        toggleBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            iconEye.classList.toggle('hidden', !isHidden);
            iconEyeOff.classList.toggle('hidden', isHidden);

            toggleBtn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    </script>

</body>
</html>
