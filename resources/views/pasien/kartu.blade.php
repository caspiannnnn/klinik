@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
  <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200" id="kartu-pasien">
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
        <div class="font-semibold">{{ $user->name }}</div>

        <div class="text-gray-500 text-xs mt-2">No. RM</div>
        <div class="font-semibold tracking-wide">{{ $user->no_rm ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">No. HP</div>
        <div class="font-semibold">{{ $user->telepon ?? $user->no_hp ?? '-' }}</div>

        <div class="text-gray-500 text-xs mt-2">Alamat</div>
        <div class="font-semibold">
          {{ $user->alamat ?? $user->address ?? $user->alamat_lengkap ?? '-' }}
        </div>
      </div>

      <div class="flex items-center justify-center">
        @if($user->qr_url)
          <img id="qrImage" src="{{ $user->qr_url }}" alt="QR Pasien" class="w-32 h-32" crossorigin="anonymous">
        @endif
      </div>
    </div>

    <div class="mt-6 flex items-center justify-between no-print">
      {{-- ✅ Unduh jadi PNG (bukan SVG) --}}
      <a href="#"
         id="downloadQrPng"
         class="text-sm text-blue-600 underline">
        Unduh QR
      </a>

      <button onclick="window.print()" class="text-sm px-3 py-1 rounded bg-blue-600 text-white">Cetak</button>
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

<script>
(function () {
  const btn = document.getElementById('downloadQrPng');
  const img = document.getElementById('qrImage');
  if (!btn || !img) return;

  function downloadDataUrl(dataUrl, filename) {
    const a = document.createElement('a');
    a.href = dataUrl;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();
  }

  btn.addEventListener('click', async function (e) {
    e.preventDefault();

    // Pastikan gambar sudah ke-load
    if (!img.complete) {
      await new Promise(resolve => img.addEventListener('load', resolve, { once: true }));
    }

    const canvas = document.createElement('canvas');
    const w = img.naturalWidth || 256;
    const h = img.naturalHeight || 256;
    canvas.width = w;
    canvas.height = h;

    const ctx = canvas.getContext('2d');
    // background putih biar QR bersih
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, w, h);

    try {
      ctx.drawImage(img, 0, 0, w, h);
      const dataUrl = canvas.toDataURL('image/png');
      const filename = `qr-pasien-{{ $user->username ?? $user->id }}.png`;
      downloadDataUrl(dataUrl, filename);
    } catch (err) {
      // Kalau server tidak mengizinkan canvas membaca SVG cross-origin
      alert('Gagal mengunduh PNG. Pastikan file QR bisa diakses (storage:link) dan bukan blocked oleh browser.');
      console.error(err);
    }
  });
})();
</script>
@endsection
