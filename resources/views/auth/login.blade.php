<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
  .font-modify {
    font-family: "Poppins", sans-serif;
  }
</style>

<body class="bg-blue-50 flex items-center justify-center h-screen font-modify">

    <form action="{{ route('login') }}" method="POST"
          autocomplete="off"
          class="bg-white border border-blue-200 p-8 rounded-2xl shadow-lg w-96">
        @csrf

        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16">
        </div>

        <h1 class="text-2xl text-blue-600 font-bold mb-6 text-center">Selamat Datang SIM HealtEase</h1>

        <!-- Email -->
        <div class="mb-4">
            <label class="block mb-1 text-gray-700 font-medium">Email</label>
            <input type="email" name="email" required autocomplete="off"
                   class="w-full px-4 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label class="block mb-1 text-gray-700 font-medium">Password</label>

            <div class="relative">
                <input id="password" type="password" name="password" required
                       autocomplete="new-password"
                       class="w-full px-4 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12">

                <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3"
                        aria-label="Tampilkan password">
                    <!-- Eye Open -->
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>

                    <!-- Eye Closed -->
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.733 5.076A10.744 10.744 0 0 1 12 5c7 0 10 7 10 7a18.4 18.4 0 0 1-1.67 2.68"/>
                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                        <path d="M14.12 14.12a3 3 0 0 1-4.24-4.24"/>
                        <path d="M1 1l22 22"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Button -->
        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-md transition duration-300">
            Login
        </button>

        <!-- Register Link -->
        <p class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-500 hover:underline font-medium">Daftar di sini</a>
        </p>
    </form>

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

</body>
</html>
