@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Dashboard Pasien</h1>
    <p class="text-lg mb-4">Selamat datang, {{ Auth::user()->name }}!</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
        <!-- Card for Jadwal Dokter -->
        <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
            <div class="flex items-center mb-4">
                <img src="{{ asset('images/jadwal-dokter.png') }}"
                    alt="Jadwal Dokter" class="mr-3">

            </div>
            <h2 class="text-xl font-semibold">Jadwal Dokter</h2>
            <p class="text-gray-600 mb-4">Lihat jadwal dokter yang tersedia dan pilih waktu yang sesuai.</p>
            <a href="/jadwal-dokter" class="text-blue-500 font-semibold hover:underline">
                <i class="fas fa-calendar-alt mr-1"></i> Lihat Jadwal
            </a>
        </div>

        <!-- Card for Pendaftaran Saya -->
        <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
            <div class="flex items-center mb-4">
                <img src="{{ asset('images/konsultasi.png') }}"
                    alt="Pendaftaran" class="mr-3">
            </div>
            <h2 class="text-xl font-semibold">Pendaftaran Saya</h2>

            <p class="text-gray-600 mb-4">Lihat status pendaftaran, nomor antrian, dan lakukan reschedule jika diperlukan.</p>
            <a href="/pendaftaran-saya" class="text-blue-500 font-semibold hover:underline">
                <i class="fas fa-list mr-1"></i> Lihat Pendaftaran
            </a>
        </div>

        <!-- Card for Rekam Medis -->
        <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
            <div class="flex items-center mb-4">
                <img src="{{ asset('images/rekam-medis.png') }}"
                    alt="Rekam Medis" class="mr-3">
            </div>
            <h2 class="text-xl font-semibold">Rekam Medis</h2>

            <p class="text-gray-600 mb-4">Lihat riwayat rekam medis Anda dan informasi kesehatan lainnya.</p>
            <a href="/rekam-medis" class="text-blue-500 font-semibold hover:underline">
                <i class="fas fa-file-medical mr-1"></i> Lihat Rekam Medis
            </a>
        </div>
    </div>
@endsection
