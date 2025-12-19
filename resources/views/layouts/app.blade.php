<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SIM HealtEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .font-modify {
        font-family: "Poppins", sans-serif;
        font-weight: 300;
        font-style: normal;
    }
</style>

<body class="bg-blue-50 text-gray-800 font-modify">

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white shadow-lg p-6">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik" class="h-12 mb-2">
            </div>

            <nav class="space-y-4">
                {{-- Role: Pasien --}}
                @if(Auth::user()->role === 'pasien')

                    @php
                        $unreadNotifCount = \App\Models\Notifikasi::where('user_id', Auth::id())
                            ->where('dibaca', false)
                            ->count();
                    @endphp

                    <a href="/pasien" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    <a href="/jadwal-dokter"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-calendar-alt mr-2"></i> Jadwal Dokter
                    </a>
                    <a href="/pendaftaran"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i> Pendaftaran
                    </a>

                    {{-- ✅ MENU BARU: Pendaftaran Saya --}}
                    <a href="{{ route('pendaftaran.saya') }}"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-list mr-2"></i> Pendaftaran Saya
                    </a>

                    <a href="/rekam-medis"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-folder mr-2"></i> Rekam Medis
                    </a>
                    <a href="/pasien/tagihan"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-credit-card mr-2"></i> Pembayaran
                    </a>

                    <a href="/notifikasi"
                        class="flex items-center justify-between py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <span class="flex items-center">
                            <i class="fas fa-bell mr-2"></i> Notifikasi
                        </span>

                        @if($unreadNotifCount > 0)
                            <span class="inline-flex items-center justify-center text-xs font-bold text-white bg-red-500 rounded-full min-w-[20px] h-5 px-1">
                                {{ $unreadNotifCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('pasien.kartu') }}"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-id-card mr-2"></i> Kartu Pasien
                    </a>

                {{-- Role: Dokter --}}
                @elseif(Auth::user()->role === 'dokter')
                    <a href="/dokter" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('dokter.jadwal.index') }}"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-calendar-check mr-2"></i> Manajemen Jadwal
                    </a>
                    <a href="/dokter/pendaftar"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-list-alt mr-2"></i> Semua Pendaftaran
                    </a>
                    <a href="/dokter/pasien"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-users mr-2"></i> Data Pasien
                    </a>

                    <a href="{{ route('dokter.daftar_rekam_medis') }}"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-stethoscope mr-2"></i> Daftar Rekam Medis
                    </a>

                {{-- Role: Resepsionis --}}
                @elseif(Auth::user()->role === 'resepsionis')
                    <a href="/resepsionis" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    <a href="/resepsionis/daftar-pasien"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Offline
                    </a>
                    <a href="/resepsionis/pendaftaran"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i> Pendaftaran Pasien
                    </a>

                    {{-- ✅ Menu QR Scan --}}
                    <a href="{{ route('resepsionis.qr_scan') }}"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-qrcode mr-2"></i> QR Scan
                    </a>

                {{-- Role: Admin --}}
                @elseif(Auth::user()->role === 'admin')
                    <a href="/admin" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-users-cog mr-2"></i> Dashboard Admin
                    </a>
                    <a href="/admin/dokter"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-user-md mr-2"></i> List Dokter
                    </a>
                    <a href="/admin/pasien"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-user-md mr-2"></i> List Pasien
                    </a>
                    <a href="/admin/resepsionis"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-user-md mr-2"></i> List Resepsionis
                    </a>
                    <a href="/admin/pembayaran/create"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-credit-card mr-2"></i> Tambah Pembayaran
                    </a>
                    <a href="/admin/pembayaran/konfirmasi"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-credit-card mr-2"></i> Konfirmasi Pembayaran
                    </a>
                    <a href="/admin/pembayaran"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-credit-card mr-2"></i> Data Pembayaran
                    </a>
                    <a href="/admin/laporan"
                        class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-chart-line mr-2"></i> Laporan
                    </a>
                @endif

                <a href="/login" class="flex items-center py-2 px-4 rounded hover:bg-blue-100 transition duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </aside>

        {{-- Konten utama --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
