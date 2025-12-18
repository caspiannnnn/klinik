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
          {{-- QR sumbernya SVG (aman tanpa imagick), tapi bisa diunduh jadi PNG --}}
          <img id="qrImage" src="{{ $user->qr_url }}" alt="QR Pasien" class="w-32 h-32">
        @endif
      </div>
    </div>

    <div class="mt-6 flex items-center justify-between no-print">
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
    body * { visibility: hidden !important; }
    #kartu-pasien, #kartu-pasien * { visibility: visible !important; }

    #kartu-pasien {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      box-shadow: none !important;
      border: 1px solid #e5e7eb !important;
      margin: 0 !important;
    }

    .no-print { display: none !important; }
  }
</style>

<script>
(function () {
  const btn = document.getElementById('downloadQrPng');
  const img = document.getElementById('qrImage');
  if (!btn || !img) return;

  function downloadBlob(blob, filename) {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
  }

  async function fetchAsText(url) {
    const res = await fetch(url, { cache: 'no-store' });
    if (!res.ok) throw new Error('Gagal mengambil QR (fetch).');
    return await res.text();
  }

  btn.addEventListener('click', async function (e) {
    e.preventDefault();

    try {
      // 1) Ambil SVG raw dari URL (storage/...)
      const svgText = await fetchAsText(img.src);

      // 2) Ubah SVG text jadi Blob URL
      const svgBlob = new Blob([svgText], { type: 'image/svg+xml;charset=utf-8' });
      const svgUrl = URL.createObjectURL(svgBlob);

      // 3) Render SVG ke canvas
      const canvas = document.createElement('canvas');
      const size = 600; // lebih besar biar tajam saat scan
      canvas.width = size;
      canvas.height = size;

      const ctx = canvas.getContext('2d');
      ctx.fillStyle = '#ffffff';
      ctx.fillRect(0, 0, size, size);

      const tempImg = new Image();
      tempImg.onload = function () {
        ctx.drawImage(tempImg, 0, 0, size, size);

        canvas.toBlob(function (pngBlob) {
          if (!pngBlob) {
            alert('Gagal membuat PNG.');
            URL.revokeObjectURL(svgUrl);
            return;
          }
          const filename = `qr-pasien-{{ $user->username ?? $user->id }}.png`;
          downloadBlob(pngBlob, filename);
          URL.revokeObjectURL(svgUrl);
        }, 'image/png');
      };

      tempImg.onerror = function () {
        URL.revokeObjectURL(svgUrl);
        alert('Gagal memuat SVG untuk dikonversi ke PNG.');
      };

      tempImg.src = svgUrl;
    } catch (err) {
      console.error(err);
      alert('Gagal mengunduh PNG. Pastikan storage:link sudah dibuat dan QR bisa diakses.');
    }
  });
})();
</script>
@endsection
