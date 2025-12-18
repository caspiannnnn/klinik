<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Klinik HealthEase — Klinik Modern & Nyaman</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
    .font-modify { font-family: "Poppins", sans-serif; }
  </style>
</head>
<body class="bg-blue-50 text-gray-800 font-modify">

  <!-- Navbar -->
  <nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <a href="/" class="text-2xl font-bold text-blue-600">Klinik HealthEase</a>
      <div class="flex items-center gap-6">
        <a href="/login" class="px-4 py-2 text-blue-600 font-medium hover:underline">Login</a>
        <a href="/register" class="hidden sm:inline-block bg-blue-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-blue-700">Konsultasi Sekarang</a>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="flex flex-col md:flex-row items-center justify-between px-6 py-16 md:py-24 max-w-7xl mx-auto">
    <div class="md:w-1/2 mb-10 md:mb-0" data-aos="fade-right">
      <h1 class="text-4xl md:text-5xl font-bold text-blue-700 leading-tight mb-6">
        Sistem Informasi Klinik Modern & Nyaman
      </h1>
      <p class="text-lg text-gray-600 mb-8">
        Daftar, konsultasi, dan pantau hasil perawatan tanpa repot—semua dalam satu sistem yang aman dan terintegrasi.
      </p>
      <div class="flex gap-3">
        <a href="/register" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-xl text-lg font-semibold shadow hover:bg-blue-700 transition">
          Konsultasi Sekarang
        </a>
        <a href="#fitur" class="inline-block bg-white text-blue-600 border border-blue-200 px-6 py-3 rounded-xl text-lg font-semibold shadow hover:bg-blue-50 transition">
          Lihat Fitur
        </a>
      </div>
    </div>
    <div class="md:w-1/2" data-aos="zoom-in">
      <img src="{{ asset('images/login.png') }}" alt="Ilustrasi Klinik" class="w-full max-w-md mx-auto">
    </div>
  </section>

  <!-- Nilai Utama -->
  <section class="py-12 bg-white" id="fitur">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl font-bold mb-8 text-blue-600 text-center" data-aos="fade-up">Mengapa HealtEase?</h2>
      <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-blue-50 rounded-xl p-6 shadow-sm" data-aos="fade-up">
          <h3 class="font-semibold text-blue-600 mb-2">Cepat & Tanpa Antre</h3>
          <p>Pendaftaran online dengan tiket digital dan notifikasi otomatis.</p>
        </div>
        <div class="bg-blue-50 rounded-xl p-6 shadow-sm" data-aos="fade-up" data-aos-delay="50">
          <h3 class="font-semibold text-blue-600 mb-2">Dokter Terverifikasi</h3>
          <p>Tenaga medis berpengalaman dan komunikatif.</p>
        </div>
        <div class="bg-blue-50 rounded-xl p-6 shadow-sm" data-aos="fade-up" data-aos-delay="100">
          <h3 class="font-semibold text-blue-600 mb-2">Rekam Medis Digital</h3>
          <p>Hasil pemeriksaan, resep, dan riwayat kunjungan tersimpan aman.</p>
        </div>
        <div class="bg-blue-50 rounded-xl p-6 shadow-sm" data-aos="fade-up" data-aos-delay="150">
          <h3 class="font-semibold text-blue-600 mb-2">Pembayaran Terintegrasi</h3>
          <p>Fleksibel: tunai, transfer, dan nontunai populer.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Layanan -->
  <section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-blue-600 mb-10 text-center" data-aos="fade-up">Layanan Utama</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition" data-aos="zoom-in">
          <h3 class="font-semibold text-lg mb-2 text-blue-500">Konsultasi Dokter Umum</h3>
          <p>Keluhan harian, skrining awal, dan rujukan terarah.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition" data-aos="zoom-in" data-aos-delay="50">
          <h3 class="font-semibold text-lg mb-2 text-blue-500">Poli Gigi</h3>
          <p>Scaling, tambal, cabut, serta edukasi gigi anak.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition" data-aos="zoom-in" data-aos-delay="100">
          <h3 class="font-semibold text-lg mb-2 text-blue-500">Laboratorium Dasar</h3>
          <p>Tes rutin hemat waktu, hasil tersimpan digital.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing / Licensing -->
  <section id="pricing" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-blue-600 mb-4 text-center" data-aos="fade-up">Paket Lisensi HealthEase</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <!-- Trial -->
        <div class="bg-blue-50 p-8 rounded-xl shadow-sm border border-blue-100" data-aos="zoom-in">
          <h3 class="text-xl font-semibold text-blue-700 mb-2">Free Trial</h3>
          <p class="text-gray-600 mb-6">Coba sistem sebelum kontrak dimulai untuk memastikan sesuai kebutuhan klinik.</p>

          <div class="mb-6">
            <p class="text-4xl font-bold text-blue-700">Gratis</p>
            <p class="text-sm text-gray-500 mt-1">Demo uji coba (tanpa komitmen)</p>
          </div>

          <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Akses fitur inti (antrian, pasien, dashboard)</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Simulasi alur kerja admin, resepsionis, dokter</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Pendampingan setup awal</li>
          </ul>

          <div class="mt-8">
            <a href="https://wa.me/6281243447272" class="inline-block bg-white text-blue-600 border border-blue-200 px-5 py-3 rounded-xl font-semibold shadow hover:bg-blue-50 transition">
              Minta Demo
            </a>
          </div>
        </div>

        <!-- 1 Tahun -->
        <div class="bg-white p-8 rounded-xl shadow-md border border-blue-100" data-aos="zoom-in" data-aos-delay="50">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-xl font-semibold text-blue-700">Lisensi 1 Tahun</h3>
            <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold">Populer</span>
          </div>

          <p class="text-gray-600 mb-6">Cocok untuk klinik yang ingin mulai digitalisasi operasional dengan biaya terukur.</p>

          <div class="mb-6">
            <p class="text-4xl font-bold text-blue-700">Custom</p>
            <p class="text-sm text-gray-500 mt-1">Harga menyesuaikan kebutuhan & skala klinik</p>
          </div>

          <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Sistem antrian & pendaftaran online</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Manajemen pasien & rekam medis digital</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Dashboard admin, dokter, resepsionis</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Update fitur berkala & pemeliharaan sistem</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Dukungan teknis selama masa kontrak</li>
          </ul>

          <div class="mt-8">
            <a href="https://wa.me/6281243447272" class="inline-block bg-blue-600 text-white px-5 py-3 rounded-xl font-semibold shadow hover:bg-blue-700 transition">
              Konsultasi Paket
            </a>
          </div>
        </div>

        <!-- 2 Tahun -->
        <div class="bg-blue-50 p-8 rounded-xl shadow-sm border border-blue-100" data-aos="zoom-in" data-aos-delay="100">
          <h3 class="text-xl font-semibold text-blue-700 mb-2">Lisensi 2 Tahun</h3>
          <p class="text-gray-600 mb-6">Pilihan hemat untuk kerja sama jangka panjang dengan dukungan lebih stabil.</p>

          <div class="mb-6">
            <p class="text-4xl font-bold text-blue-700">Custom</p>
            <p class="text-sm text-gray-500 mt-1">Kontrak lebih panjang, value lebih tinggi</p>
          </div>

          <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Semua fitur paket 1 tahun</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Prioritas dukungan & maintenance</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Penyesuaian minor sesuai kebutuhan klinik</li>
            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span> Pembaruan fitur berkelanjutan</li>
          </ul>

          <div class="mt-8">
            <a href="https://wa.me/6281243447272" class="inline-block bg-white text-blue-600 border border-blue-200 px-5 py-3 rounded-xl font-semibold shadow hover:bg-blue-50 transition">
              Ajukan Penawaran
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-16 bg-gradient-to-r from-blue-600 to-blue-500 text-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h3 class="text-3xl font-bold mb-4" data-aos="fade-up">Siap mulai perawatan yang lebih nyaman?</h3>
      <p class="mb-8 opacity-90" data-aos="fade-up" data-aos-delay="50">Buat janji sekarang dan rasakan proses klinik yang rapi dan terukur.</p>
      <a href="/register" class="inline-block bg-white text-blue-600 font-semibold px-6 py-3 rounded-xl hover:bg-blue-50">Konsultasi Sekarang</a>
    </div>
  </section>

  <!-- Hubungi Kami (ICON IG + WA) -->
  <section id="kontak" class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-6 text-center" data-aos="fade-up">
      <h2 class="text-3xl font-bold mb-3 text-blue-600">Hubungi Kami</h2>
      <p class="text-gray-600 mb-8">
        Tertarik menggunakan sistem ini untuk klinik Anda? Hubungi kami melalui Instagram atau WhatsApp di bawah ini ya!
      </p>

      <!-- GANTI INI -->
      <!-- Instagram username tanpa @ -->
      <!-- Nomor WA format internasional tanpa + dan tanpa spasi, contoh: 62812xxxx -->
      <div class="grid sm:grid-cols-2 gap-4 max-w-xl mx-auto">
        <!-- Instagram -->
        <a
          href="https://instagram.com/healthease.id"
          target="_blank"
          rel="noopener"
          class="group flex items-center justify-center gap-3 px-5 py-4 rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition"
        >
          <!-- IG ICON -->
          <svg class="w-6 h-6 text-pink-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5A4.25 4.25 0 0 0 20.5 16.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5Zm9.65 2.55a.85.85 0 1 1 0 1.7.85.85 0 0 1 0-1.7ZM12 6.5A5.5 5.5 0 1 1 6.5 12 5.51 5.51 0 0 1 12 6.5Zm0 1.5A4 4 0 1 0 16 12a4 4 0 0 0-4-4Z"/>
          </svg>

          <div class="text-left">
            <div class="text-xs text-gray-500">Instagram</div>
            <div class="font-semibold text-gray-800 group-hover:text-blue-600 transition">
              @healthease
            </div>
          </div>
        </a>

        <!-- WhatsApp -->
        <a
          href="https://wa.me//+6281243447272"
          target="_blank"
          rel="noopener"
          class="group flex items-center justify-center gap-3 px-5 py-4 rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition"
        >
          <!-- WA ICON -->
          <svg class="w-6 h-6 text-green-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12.04 2C6.53 2 2.06 6.31 2.06 11.63c0 2.09.74 4.04 2 5.62L3 22l4.98-1.56a10.3 10.3 0 0 0 4.06.82c5.5 0 9.98-4.31 9.98-9.63C22.02 6.31 17.54 2 12.04 2Zm0 17.56c-1.28 0-2.51-.27-3.63-.79l-.26-.12-2.95.93.98-2.76-.18-.28a7.79 7.79 0 0 1-1.26-4.27c0-4.33 3.66-7.86 8.3-7.86 4.58 0 8.3 3.53 8.3 7.86 0 4.33-3.72 7.86-8.3 7.86Zm4.63-5.64c-.25-.12-1.48-.72-1.71-.8-.23-.08-.4-.12-.57.12-.17.24-.66.8-.8.97-.15.17-.3.19-.55.06-.25-.12-1.05-.38-2-1.22-.74-.64-1.24-1.44-1.39-1.68-.14-.24-.02-.37.1-.49.11-.11.25-.28.37-.42.12-.14.17-.24.25-.4.08-.16.04-.3-.02-.42-.06-.12-.57-1.34-.78-1.83-.2-.48-.41-.41-.57-.42h-.49c-.17 0-.45.06-.68.3-.23.24-.9.88-.9 2.14 0 1.26.93 2.48 1.06 2.65.12.17 1.83 2.86 4.45 4.01.62.27 1.1.43 1.48.55.62.2 1.19.17 1.64.1.5-.07 1.48-.6 1.69-1.19.2-.59.2-1.1.14-1.19-.06-.09-.23-.14-.48-.27Z"/>
          </svg>

          <div class="text-left">
            <div class="text-xs text-gray-500">WhatsApp</div>
            <div class="font-semibold text-gray-800 group-hover:text-blue-600 transition">
              0812-4344-7272
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <footer class="text-center py-6 text-sm text-gray-500">
    &copy; 2025 Klinik HealtEase. All rights reserved.
  </footer>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init({ duration: 900, once: true });</script>
</body>
</html>
