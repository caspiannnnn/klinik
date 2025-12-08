@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
  <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
    <h1 class="text-2xl font-bold text-blue-700 mb-2">QR Scan Pasien</h1>
    <p class="text-sm text-gray-600 mb-6">
      Gunakan kamera untuk scan QR kartu pasien atau upload gambar QR. Setelah terbaca, sistem akan otomatis menampilkan data & rekam medis pasien.
    </p>

    {{-- STATUS --}}
    <div id="scanStatus" class="mb-4 text-sm text-gray-600">
      Status: <span class="font-semibold" id="scanStatusText">Siap</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      {{-- CAMERA SCAN --}}
      <div class="border border-blue-200 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
          <h2 class="font-semibold text-blue-700">
            <i class="fas fa-camera mr-2"></i> Scan Kamera
          </h2>
          <div class="flex gap-2">
            <button type="button" id="btnStart"
              class="text-sm px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
              Mulai
            </button>
            <button type="button" id="btnStop" disabled
              class="text-sm px-3 py-1 rounded bg-gray-200 text-gray-600 cursor-not-allowed">
              Stop
            </button>
          </div>
        </div>

        <div class="text-xs text-gray-500 mb-2">
          Izinkan akses kamera. Arahkan QR ke kotak pemindai.
        </div>

        <div id="reader" class="rounded-lg overflow-hidden"></div>

        <div class="mt-3 text-xs text-gray-500">
          Jika kamera tidak muncul: pastikan pakai <b>https</b> atau localhost, dan izinkan permission camera.
        </div>
      </div>

      {{-- UPLOAD IMAGE --}}
      <div class="border border-blue-200 rounded-xl p-4">
        <h2 class="font-semibold text-blue-700 mb-3">
          <i class="fas fa-image mr-2"></i> Upload Gambar QR
        </h2>

        <div class="text-xs text-gray-500 mb-2">
          Pilih gambar yang berisi QR (JPG/PNG). Sistem akan decode otomatis.
        </div>

        <input type="file" id="qrFile" accept="image/*"
          class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

        <button type="button" id="btnDecodeFile"
          class="w-full mt-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-md transition duration-300">
          Decode dari Gambar
        </button>

        <div class="mt-4 text-xs text-gray-500">
          Tips: gunakan gambar yang jelas (tidak blur) dan QR tidak terpotong.
        </div>
      </div>
    </div>

    {{-- RESULT PREVIEW --}}
    <div class="mt-6">
      <div class="text-sm text-gray-600">Hasil terbaca:</div>
      <div id="scanResult" class="mt-1 p-3 rounded-lg border bg-gray-50 text-sm text-gray-800 break-all">
        -
      </div>
    </div>
  </div>
</div>

{{-- html5-qrcode (CDN) --}}
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
  const statusText = document.getElementById('scanStatusText');
  const resultBox = document.getElementById('scanResult');

  const btnStart = document.getElementById('btnStart');
  const btnStop = document.getElementById('btnStop');

  const qrFile = document.getElementById('qrFile');
  const btnDecodeFile = document.getElementById('btnDecodeFile');

  // ✅ pakai route template Laravel biar pasti benar
  const scanRouteTemplate = @json(route('pasien.scan', ['token' => '__TOKEN__']));

  let html5Qrcode = null;
  let isRunning = false;

  function setStatus(text) {
    statusText.textContent = text;
  }

  function setResult(text) {
    resultBox.textContent = text || '-';
  }

  function normalizeToken(token) {
    if (!token) return null;

    // trim + hapus newline/spasi
    let t = String(token).trim().replace(/\s+/g, '');

    // buang query/hash jika ada
    t = t.split('?')[0].split('#')[0];

    // buang suffix aneh seperti ":1"
    t = t.replace(/:\d+$/g, '');

    // whitelist karakter token (uuid biasanya a-z0-9-)
    t = t.replace(/[^a-zA-Z0-9\-]/g, '');

    return t || null;
  }

  function extractToken(decodedText) {
    if (!decodedText) return null;
    const raw = String(decodedText).trim();

    // 1) Kalau QR berisi URL scan pasien, ambil token tepat setelah /scan/pasien/
    // contoh: http://127.0.0.1:8000/scan/pasien/xxxxxxxx
    const match = raw.match(/\/scan\/pasien\/([a-zA-Z0-9\-]+)/);
    if (match && match[1]) return normalizeToken(match[1]);

    // 2) Kalau QR hanya berisi token saja
    if (!raw.includes('/')) return normalizeToken(raw);

    // 3) Kalau berisi URL/path lain, ambil segmen terakhir
    try {
      const u = new URL(raw);
      const parts = u.pathname.split('/').filter(Boolean);
      return normalizeToken(parts.length ? parts[parts.length - 1] : null);
    } catch (e) {
      const parts = raw.split('/').filter(Boolean);
      return normalizeToken(parts.length ? parts[parts.length - 1] : null);
    }
  }

  function goToPatientScan(decodedText) {
    setResult(decodedText);

    const token = extractToken(decodedText);
    if (!token) {
      setStatus('Gagal: token tidak valid / tidak ditemukan');
      return;
    }

    setStatus('Berhasil terbaca. Mengalihkan...');
    const target = scanRouteTemplate.replace('__TOKEN__', encodeURIComponent(token));
    window.location.href = target;
  }

  async function startCameraScan() {
    if (isRunning) return;

    setStatus('Memulai kamera...');
    btnStart.disabled = true;
    btnStart.classList.add('cursor-not-allowed', 'opacity-70');

    html5Qrcode = new Html5Qrcode("reader");
    const config = {
      fps: 10,
      qrbox: { width: 250, height: 250 },
      rememberLastUsedCamera: true,
      supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
    };

    try {
      const cameras = await Html5Qrcode.getCameras();
      const camId = cameras && cameras.length
        ? (cameras.find(c => /back|rear|environment/i.test(c.label))?.id || cameras[0].id)
        : null;

      if (!camId) {
        setStatus('Kamera tidak ditemukan');
        btnStart.disabled = false;
        btnStart.classList.remove('cursor-not-allowed', 'opacity-70');
        return;
      }

      await html5Qrcode.start(
        camId,
        config,
        (decodedText) => {
          stopCameraScan(true);
          goToPatientScan(decodedText);
        },
        () => {}
      );

      isRunning = true;
      setStatus('Kamera aktif. Arahkan QR ke kotak.');
      btnStop.disabled = false;
      btnStop.classList.remove('cursor-not-allowed', 'opacity-70');
      btnStop.classList.add('bg-red-500', 'text-white', 'hover:bg-red-600');
    } catch (err) {
      setStatus('Gagal memulai kamera. Cek permission.');
      btnStart.disabled = false;
      btnStart.classList.remove('cursor-not-allowed', 'opacity-70');
    }
  }

  async function stopCameraScan(silent = false) {
    if (!html5Qrcode || !isRunning) {
      if (!silent) setStatus('Kamera belum berjalan');
      return;
    }

    try {
      await html5Qrcode.stop();
      await html5Qrcode.clear();
    } catch (e) {}

    isRunning = false;
    setStatus(silent ? 'Memproses hasil...' : 'Kamera berhenti');

    btnStart.disabled = false;
    btnStart.classList.remove('cursor-not-allowed', 'opacity-70');

    btnStop.disabled = true;
    btnStop.className = "text-sm px-3 py-1 rounded bg-gray-200 text-gray-600 cursor-not-allowed";
  }

  btnStart.addEventListener('click', startCameraScan);
  btnStop.addEventListener('click', () => stopCameraScan(false));

  btnDecodeFile.addEventListener('click', async () => {
    const file = qrFile.files && qrFile.files[0] ? qrFile.files[0] : null;
    if (!file) {
      setStatus('Pilih file gambar dulu');
      return;
    }

    // kalau kamera lagi jalan, stop dulu supaya tidak bentrok
    if (isRunning) await stopCameraScan(true);

    setStatus('Mendecode gambar...');
    try {
      // gunakan instance baru untuk scan file
      const fileScanner = new Html5Qrcode("reader");
      const decodedText = await fileScanner.scanFile(file, true);

      // bersihin UI scanner
      try { await fileScanner.clear(); } catch(e) {}

      goToPatientScan(decodedText);
    } catch (e) {
      setStatus('Gagal decode dari gambar (QR tidak terbaca).');
    }
  });

  window.addEventListener('beforeunload', () => {
    if (html5Qrcode && isRunning) {
      try { html5Qrcode.stop(); } catch (e) {}
    }
  });
</script>
@endsection
