@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <div class="bg-white shadow rounded-xl p-6 text-center">
        <h2 class="text-2xl font-bold text-blue-800 mb-2">Pendaftaran Berhasil</h2>
        <p class="text-gray-600">Tunjukkan QR Code ini ke resepsionis saat tiba.</p>

        <div class="mt-6">
            <div class="text-sm text-gray-500">Nomor Antrian</div>
            <div class="text-4xl font-extrabold tracking-widest">{{ $pendaftaran->kode_antrian }}</div>
        </div>

        <div class="mt-6 flex justify-center">
            <img src="{{ asset('storage/'.$pendaftaran->qr_path) }}" alt="QR Code" class="w-56 h-56">
        </div>

        <div class="mt-4 text-sm text-gray-500">
            Nama: <span class="font-medium">{{ $pendaftaran->nama }}</span><br>
            Status: <span class="font-medium capitalize">{{ $pendaftaran->status }}</span>
        </div>
    </div>
</div>
@endsection
