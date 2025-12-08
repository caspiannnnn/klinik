@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

  {{-- KARTU PASIEN (print area) --}}
  <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200 mb-6" id="kartu-pasien">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-xl font-bold text-blue-700">Kartu Pasien</h2>
        <div class="text-sm text-gray-500">Tunjukkan ke resepsionis/dokter</div>
      </div>
      <img src="{{ asset('images/logo.png') }}" class="h-10" alt="Logo">
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div class="col-span-2 space-y-2">
        <div class="text-gray-500 text-xs">Nama</div>
        <div class="font-semibold">{{ $pasien->name }}</div>

        <div class="text-gray-500 text-xs mt-2">No. RM</div>
        <div class="font-semibold tracking-wide">{{ $pasien->no_rm ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">No. HP</div>
        <div class="font-semibold">{{ $pasien->telepon ?? $pasien->no_hp ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">Alamat</div>
        <div class="font-semibold">{{ $pasien->alamat ?? '-' }}</div>
      </div>

      <div class="flex items-center justify-center">
        @if($pasien->qr_url)
          <img src="{{ $pasien->qr_url }}" alt="QR Pasien" class="w-32 h-32">
        @endif
      </div>
    </div>

    <div class="mt-6 flex items-center justify-between no-print">
      <a href="{{ $pasien->qr_url }}" download class="text-sm text-blue-600 underline">Unduh QR</a>
      <button onclick="window.print()" class="text-sm px-3 py-1 rounded bg-blue-600 text-white">Cetak</button>
    </div>
  </div>

  {{-- DETAIL + RIWAYAT --}}
  <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
    <h2 class="text-xl font-bold mb-4">Data Pasien</h2>

    <div class="grid grid-cols-3 gap-4">
      <div class="col-span-2 space-y-1">
        <div><span class="text-gray-500 text-sm">Nama:</span> <strong>{{ $pasien->name }}</strong></div>
        <div><span class="text-gray-500 text-sm">No. RM:</span> <strong>{{ $pasien->no_rm ?? '-' }}</strong></div>
        <div><span class="text-gray-500 text-sm">Telepon:</span> <strong>{{ $pasien->telepon ?? $pasien->no_hp ?? '-' }}</strong></div>
        <div><span class="text-gray-500 text-sm">Alamat:</span> <strong>{{ $pasien->alamat ?? '-' }}</strong></div>
      </div>
      <div class="text-right">
        @if($pasien->qr_url)
          <img src="{{ $pasien->qr_url }}" class="w-28 h-28 inline-block" alt="QR">
        @endif
      </div>
    </div>

    <hr class="my-6">

    <h3 class="font-semibold mb-2">Riwayat / Daftar Pendaftaran</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left text-gray-500">
            <th class="py-2">Tanggal</th>
            <th class="py-2">Keluhan</th>
            <th class="py-2">Status</th>
            <th class="py-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pendaftarans as $p)
            <tr class="border-t">
              <td class="py-2">{{ $p->created_at->format('d/m/Y H:i') }}</td>
              <td class="py-2">{{ \Illuminate\Support\Str::limit($p->keluhan, 40) }}</td>
              <td class="py-2 capitalize">{{ $p->status }}</td>
              <td class="py-2">
                <a href="{{ route('dokter.rekam_medis.show', $p->id) }}"
                   class="text-blue-600 underline">Buka</a>
              </td>
            </tr>
          @empty
            <tr><td class="py-3 text-gray-500" colspan="4">Belum ada pendaftaran.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <hr class="my-6">

    <h3 class="font-semibold mb-2">Rekam Medis Pasien</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left text-gray-500">
            <th class="py-2">Tanggal</th>
            <th class="py-2">Dokter</th>
            <th class="py-2">Diagnosa</th>
            <th class="py-2">Tindakan</th>
            <th class="py-2">Catatan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rekamMedisList as $rekam)
            <tr class="border-t">
              <td class="py-2">{{ $rekam->created_at->format('d/m/Y') }}</td>
              <td class="py-2">{{ $rekam->dokter->name ?? 'Tidak diketahui' }}</td>
              <td class="py-2">{{ $rekam->diagnosa }}</td>
              <td class="py-2">{{ $rekam->tindakan }}</td>
              <td class="py-2">{{ $rekam->catatan ?? '-' }}</td>
            </tr>
          @empty
            <tr><td class="py-3 text-gray-500" colspan="5">Belum ada rekam medis.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>

<style>
  @media print {
    /* sembunyikan semua elemen halaman */
    body * {
      visibility: hidden !important;
    }

    /* tampilkan hanya kartu pasien */
    #kartu-pasien, #kartu-pasien * {
      visibility: visible !important;
    }

    /* posisikan kartu di kiri atas & rapikan */
    #kartu-pasien {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      box-shadow: none !important;
      border: 1px solid #e5e7eb !important;
      margin: 0 !important;
    }

    /* sembunyikan tombol/link saat print */
    .no-print {
      display: none !important;
    }
  }
</style>
@endsection
