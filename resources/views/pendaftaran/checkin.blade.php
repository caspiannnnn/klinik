@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Check-in Berhasil</h2>
        <div class="space-y-1">
            <div><span class="text-gray-500">Kode Antrian:</span> <strong>{{ $pendaftaran->kode_antrian }}</strong></div>
            <div><span class="text-gray-500">Nama:</span> <strong>{{ $pendaftaran->nama }}</strong></div>
            <div><span class="text-gray-500">Status:</span> <strong class="capitalize">{{ $pendaftaran->status }}</strong></div>
            <div><span class="text-gray-500">Waktu Check-in:</span> <strong>{{ optional($pendaftaran->checkin_at)->format('d/m/Y H:i') }}</strong></div>
        </div>
    </div>
</div>
@endsection
