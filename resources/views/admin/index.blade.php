@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold text-blue-800 mb-6">Dashboard Admin</h1>
    <p class="text-lg mb-4">Selamat datang, {{ Auth::user()->name }}!</p>

    {{-- Statistik Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">
                <i class="fas fa-user-injured text-blue-600 mr-2"></i> Total Pasien
            </h2>
            <p class="text-3xl font-bold text-blue-600">{{ $totalPasien }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">
                <i class="fas fa-user-md text-green-600 mr-2"></i> Total Dokter
            </h2>
            <p class="text-3xl font-bold text-green-600">{{ $totalDokter }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">
                <i class="fas fa-user-nurse text-yellow-600 mr-2"></i> Total Resepsionis
            </h2>
            <p class="text-3xl font-bold text-yellow-600">{{ $totalResepsionis }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">
                <i class="fas fa-wallet text-purple-600 mr-2"></i> Total Keuangan
            </h2>
            <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($totalKeuangan, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Aksi Admin --}}
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold text-blue-700 mb-4">
            <i class="fas fa-bolt mr-2"></i> Aksi Cepat
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.dokter.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-5 rounded-lg text-center transition">
                <i class="fas fa-user-md mr-2"></i> Tambah Dokter
            </a>
            <a href="{{ route('admin.resepsionis.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-5 rounded-lg text-center transition">
                <i class="fas fa-user-nurse mr-2"></i> Tambah Resepsionis
            </a>
            <a href="{{ route('admin.pasien.create') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-3 px-5 rounded-lg text-center transition">
                <i class="fas fa-user-injured mr-2"></i> Tambah Pasien
            </a>
        </div>
    </div>
</div>
@endsection
