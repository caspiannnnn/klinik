<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - SIM HealtEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        .font-modify {
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen font-modify">

    <form action="{{ url('/login') }}" method="POST"
          autocomplete="off"
          class="bg-white border border-blue-200 p-8 rounded-2xl shadow-lg w-96">
        @csrf

        {{-- Logo --}}
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16">
        </div>

        <h1 class="text-2xl text-blue-600 font-bold mb-6 text-center">
            Selamat Datang SIM HealtEase
        </h1>

        {{-- Error message umum --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Email --}}
        <div class="mb-4">
            <label class="block mb-1 text-gray-700 font-medium">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="email"
                   class="w-full px-4 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Password --}}
        <div class="mb-6">
            <label class="block mb-1 text-gray-700 font-medium">Password</label>

            <div class="relative">
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       class="w-full px-4 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12">

                <button type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3"
                        aria-label="Tampilkan password">
                    {{-- Eye Open --}}
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-gray-600"
                         viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>

                    {{-- Eye Closed --}}
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-gray-600 hidden"
                         viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-8-10-8a18.45 18.45 0 0 1 5.06-7.94"/>
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-4.22 6.18"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Tombol login --}}
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition">
            Login
        </button>
    </form>

    <script>
        const passwordInput  = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeOpen        = document.getElementById('eyeOpen');
        const eyeClosed      = document.getElementById('eyeClosed');

        togglePassword.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        });
    </script>
</body>
</html>
