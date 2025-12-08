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

  <!-- CTA -->
  <section class="py-16 bg-gradient-to-r from-blue-600 to-blue-500 text-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h3 class="text-3xl font-bold mb-4" data-aos="fade-up">Siap mulai perawatan yang lebih nyaman?</h3>
      <p class="mb-8 opacity-90" data-aos="fade-up" data-aos-delay="50">Buat janji sekarang dan rasakan proses klinik yang rapi dan terukur.</p>
      <a href="/register" class="inline-block bg-white text-blue-600 font-semibold px-6 py-3 rounded-xl hover:bg-blue-50">Konsultasi Sekarang</a>
    </div>
  </section>

  <!-- Hubungi Kami (selalu terakhir) -->
  <section id="kontak" class="py-16 bg-white">
    <div class="max-w-xl mx-auto px-4 text-center" data-aos="fade-up">
      <h2 class="text-3xl font-bold mb-6 text-blue-600">Hubungi Kami</h2>
      <p class="mb-6">Jika tertarik menggunakan sistem ini untuk klinik Anda, kirim pesan melalui formulir berikut.</p>
      <form class="space-y-4">
        <input type="text" placeholder="Nama Anda" class="w-full px-4 py-2 border rounded">
        <input type="email" placeholder="Email" class="w-full px-4 py-2 border rounded">
        <textarea rows="4" placeholder="Pesan" class="w-full px-4 py-2 border rounded"></textarea>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Kirim</button>
      </form>
    </div>
  </section>

  <footer class="text-center py-6 text-sm text-gray-500">
    &copy; 2025 Klinik HealtEase. All rights reserved.
  </footer>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init({ duration: 900, once: true });</script>
</body>
</html>
