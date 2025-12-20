@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Dashboard Resepsionis</h1>
    <p class="text-lg mb-4">Selamat datang, {{ Auth::user()->name }}!</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">
                <i class="fas fa-user-injured text-blue-500 mr-1"></i> Total Seluruh Pasien
            </h2>
            <p class="text-3xl font-bold text-blue-700">{{ $totalPasien ?? 0 }}</p>
        </div>

        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">
                <i class="fas fa-calendar-check text-green-500 mr-1"></i> Pendaftaran Baru
            </h2>
            <p class="text-3xl font-bold text-green-600">{{ $pendaftaranBaru ?? 0 }}</p>
        </div>

        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">
                <i class="fas fa-user-md text-purple-500 mr-1"></i> Dokter Aktif
            </h2>
            <p class="text-3xl font-bold text-purple-600">{{ $dokterAktif ?? 0 }}</p>
        </div>

        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">
                <i class="fas fa-clock text-yellow-500 mr-1"></i> Jadwal Hari Ini
            </h2>
            <p class="text-3xl font-bold text-yellow-500">{{ $jadwalHariIni ?? 0 }}</p>
        </div>
    </div>

    {{-- Aksi Cepat --}}
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold text-blue-700 mb-4">
            <i class="fas fa-bolt mr-2"></i> Aksi Resepsionis
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('resepsionis.daftar') }}" class="btn bg-blue-600 text-white px-4 py-2 rounded">
                <i class="fas fa-user-plus mr-2"></i>Daftar Pasien Baru
            </a>

            <a href="{{ route('resepsionis.pendaftaran.index') }}" class="btn bg-green-600 text-white px-4 py-2 rounded">
                <i class="fas fa-clipboard-list mr-2"></i> Lihat Pendaftaran
            </a>

            <a href="{{ route('resepsionis.qr_scan') }}" class="btn bg-yellow-500 text-white px-4 py-2 rounded">
                <i class="fas fa-qrcode mr-2"></i> Scan QR Pasien
            </a>

            {{-- Kalau route jadwal resepsionis belum ada, biarin dulu comment ini --}}
            {{-- <a href="{{ route('resepsionis.jadwal.index') }}" class="btn bg-purple-600 text-white px-4 py-2 rounded">
                <i class="fas fa-calendar-alt mr-2"></i> Jadwal Konsultasi
            </a> --}}
        </div>
    </div>
</div>
@endsection
