-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 08, 2025 at 11:31 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `healt-ease`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_dokters`
--

CREATE TABLE `jadwal_dokters` (
  `id` bigint UNSIGNED NOT NULL,
  `dokter_id` bigint UNSIGNED NOT NULL,
  `hari` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_dokters`
--

INSERT INTO `jadwal_dokters` (`id`, `dokter_id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(1, 2, 'Senin', '08:00:00', '10:00:00', '2025-12-08 04:44:17', '2025-12-08 04:44:17'),
(2, 2, 'Selasa', '10:00:00', '12:00:00', '2025-12-08 06:01:18', '2025-12-08 06:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_16_163840_create_pendaftarans_table', 1),
(5, '2025_05_16_171446_create_rekam_medis_table', 1),
(6, '2025_05_17_030655_create_jadwal_dokters_table', 1),
(7, '2025_05_17_041910_add_spesialis_to_users_table', 1),
(8, '2025_05_17_125815_create_pembayarans_table', 1),
(9, '2025_05_17_133401_update_status_column_on_pembayarans_table', 1),
(10, '2025_12_04_013557_add_qr_fields_to_pendaftarans_table', 1),
(11, '2025_12_04_024557_add_patient_qr_to_users_table', 1),
(12, '2025_12_08_000000_create_notifikasis_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasis`
--

CREATE TABLE `notifikasis` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifikasis`
--

INSERT INTO `notifikasis` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `link`, `dibaca`, `created_at`, `updated_at`) VALUES
(1, 6, 'Status Pendaftaran Diperbarui', 'Status pendaftaran Anda berubah dari menunggu menjadi Diterima.', 'pendaftaran', 'http://127.0.0.1:8000/kartu-pasien', 0, '2025-12-08 04:44:20', '2025-12-08 04:44:20'),
(2, 6, 'Rekam Medis Baru Ditambahkan', 'Dokter telah menambahkan rekam medis untuk kunjungan Anda pada 08-12-2025 11:45.', 'rekam_medis', 'http://127.0.0.1:8000/rekam-medis', 0, '2025-12-08 04:45:03', '2025-12-08 04:45:03'),
(3, 6, 'Tagihan Baru Dibuat', 'Tagihan baru (INV-AYJAOI6L) telah dibuat. Silakan cek menu Tagihan.', 'pembayaran', 'http://127.0.0.1:8000/pasien/tagihan', 0, '2025-12-08 05:49:23', '2025-12-08 05:49:23'),
(4, 6, 'Status Pembayaran Diperbarui', 'Status pembayaran Anda berubah dari \"menunggu konfirmasi\" menjadi \"lunas\".', 'pembayaran', 'http://127.0.0.1:8000/pasien/tagihan', 0, '2025-12-08 05:57:59', '2025-12-08 05:57:59'),
(5, 6, 'Status Pembayaran Diperbarui', 'Status pembayaran Anda berubah dari \"lunas\" menjadi \"lunas\".', 'pembayaran', 'http://127.0.0.1:8000/pasien/tagihan', 0, '2025-12-08 05:58:06', '2025-12-08 05:58:06'),
(6, 6, 'Status Pendaftaran Diperbarui', 'Status pendaftaran Anda berubah dari menunggu menjadi Diterima.', 'pendaftaran', 'http://127.0.0.1:8000/kartu-pasien', 0, '2025-12-08 06:01:30', '2025-12-08 06:01:30'),
(7, 6, 'Rekam Medis Baru Ditambahkan', 'Dokter telah menambahkan rekam medis untuk kunjungan Anda pada 08-12-2025 13:02.', 'rekam_medis', 'http://127.0.0.1:8000/rekam-medis', 0, '2025-12-08 06:02:02', '2025-12-08 06:02:02'),
(8, 6, 'Tagihan Baru Dibuat', 'Tagihan baru (INV-HPJODKQY) telah dibuat. Silakan cek menu Tagihan.', 'pembayaran', 'http://127.0.0.1:8000/pasien/tagihan', 0, '2025-12-08 06:02:52', '2025-12-08 06:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `kode_tagihan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bukti_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `user_id`, `kode_tagihan`, `jumlah`, `status`, `bukti_pembayaran`, `created_at`, `updated_at`) VALUES
(1, 6, 'INV-AYJAOI6L', 500000.00, 'lunas', '1765173010_Screenshot (1).png', '2025-12-08 05:49:23', '2025-12-08 05:57:59'),
(2, 6, 'INV-HPJODKQY', 2500000.00, 'lunas', 'uploads/bukti/VwwmutXEOgYGWF2PldBFHHBK64XsRDf5U6Si5hez.png', '2025-12-08 06:02:52', '2025-12-08 06:44:35'),
(3, 6, 'INV-20251208-0001', 200000.00, 'belum dibayar', NULL, '2025-12-08 07:01:56', '2025-12-08 07:01:56'),
(4, 6, 'INV-20251208-0002', 2500000.00, 'belum dibayar', NULL, '2025-12-08 07:05:29', '2025-12-08 07:05:29');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftarans`
--

CREATE TABLE `pendaftarans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keluhan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `nomor_urut` int UNSIGNED DEFAULT NULL,
  `kode_antrian` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_token` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkin_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pendaftarans`
--

INSERT INTO `pendaftarans` (`id`, `user_id`, `nama`, `tanggal_lahir`, `jenis_kelamin`, `no_hp`, `nik`, `keluhan`, `status`, `nomor_urut`, `kode_antrian`, `qr_token`, `qr_path`, `checkin_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'Mochammad Riesty Sis', '1981-02-11', 'Laki-laki', '081234567890', '1234567890123456', 'Sakit Kepala', 'menunggu', 1, 'A001', '1754150f-4e9e-4d60-854c-a86f290681fa', 'qrcodes/1754150f-4e9e-4d60-854c-a86f290681fa.svg', NULL, '2025-12-08 03:12:41', '2025-12-08 03:12:41'),
(2, 6, 'Farid Syafi', '1863-10-20', 'Laki-laki', '081234567890', '1234567890123456', 'Asam Lambung', 'Diterima', 2, 'A002', '2e2ede82-b9e0-4562-835c-4fd444b62527', 'qrcodes/2e2ede82-b9e0-4562-835c-4fd444b62527.svg', NULL, '2025-12-08 03:21:07', '2025-12-08 04:44:20'),
(3, 6, 'Farid Syafi', '1863-10-20', 'Laki-laki', '081234567890', '1234567890123456', 'Operasi Gusi', 'Diterima', 3, 'A003', '95461890-7a9c-4f30-aa95-b1dacacf4846', 'qrcodes/95461890-7a9c-4f30-aa95-b1dacacf4846.svg', NULL, '2025-12-08 05:59:44', '2025-12-08 06:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id` bigint UNSIGNED NOT NULL,
  `pendaftaran_id` bigint UNSIGNED NOT NULL,
  `dokter_id` bigint UNSIGNED NOT NULL,
  `diagnosa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tindakan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rekam_medis`
--

INSERT INTO `rekam_medis` (`id`, `pendaftaran_id`, `dokter_id`, `diagnosa`, `tindakan`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Asam Lambung', 'Diberi barbel', 'olahraga', '2025-12-08 04:45:03', '2025-12-08 04:45:03'),
(2, 2, 2, 'wangduh', 'gaswat', NULL, '2025-12-08 06:02:02', '2025-12-08 06:02:02');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','dokter','pasien','resepsionis') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pasien',
  `no_rm` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spesialis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `alamat`, `telepon`, `no_hp`, `tanggal_lahir`, `jenis_kelamin`, `nik`, `role`, `no_rm`, `qr_token`, `qr_path`, `spesialis`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin', 'admin@gmail.com', NULL, '$2y$12$K9g82NzYONUEn1j/wA2z/OYqI7.haLV/7bi.jqb3pmd72w9MZAKBK', NULL, NULL, NULL, NULL, NULL, NULL, 'admin', NULL, NULL, NULL, NULL, NULL, '2025-12-08 03:10:57', '2025-12-08 03:10:57'),
(2, 'Dokter User', 'dokter', 'dokter@gmail.com', NULL, '$2y$12$y7aPv.wbSHKY1Dy3Y3CjLO9sqRmLo7FcOXFF2sdu72EaggHRv/w9i', NULL, NULL, NULL, NULL, NULL, NULL, 'dokter', NULL, NULL, NULL, 'Ortodonti', NULL, '2025-12-08 03:10:58', '2025-12-08 05:13:57'),
(3, 'Pasien User', 'pasien', 'pasien@gmail.com', NULL, '$2y$12$9p2M4yyJGWSQL/dlbnlRAuQKi6oIzXQNFJcB0ai/GhScu6I6L.mZi', NULL, NULL, NULL, NULL, NULL, NULL, 'pasien', 'RM-202512-00003', '1f43cc6a-d5d3-4afb-ae70-ad246486ec28', 'patient_qr/1f43cc6a-d5d3-4afb-ae70-ad246486ec28.svg', NULL, NULL, '2025-12-08 03:10:58', '2025-12-08 10:44:18'),
(4, 'Resepsionis User', 'resepsionis', 'resepsionis@gmail.com', NULL, '$2y$12$TBJGoXGgUn7bB9EGtBOVjepr/NmtShNLEk89IqdlFPh8i8l.0q6z2', NULL, NULL, NULL, NULL, NULL, NULL, 'resepsionis', NULL, NULL, NULL, NULL, NULL, '2025-12-08 03:10:58', '2025-12-08 03:10:58'),
(5, 'Mochammad Riesty Sis', 'Rie', 'rie@gmail.com', NULL, '$2y$12$m6sAmH6jKOAhwmEucqwqH.4Wb3uJKX1hFWHIAK.SsoUq8Ey76Erre', NULL, NULL, '081234567890', '1981-02-11', 'Laki-laki', '1234567890123456', 'pasien', 'RM-202512-00005', 'aa83f5b4-45df-4f56-bf00-a3ced2c95d09', 'patient_qr/aa83f5b4-45df-4f56-bf00-a3ced2c95d09.svg', NULL, NULL, '2025-12-08 03:11:07', '2025-12-08 03:13:07'),
(6, 'Farid Syafi', 'Farid', 'farid@gmail.com', NULL, '$2y$12$Qrbwm8YmPdYKizuoERplk.DoEpGnvA2IywPJTot2Ix8dR8H/8bnbq', 'Jl. Pasuruan Pandaan', NULL, '081234567890', '1863-10-20', 'Laki-laki', '1234567890123456', 'pasien', 'RM-202512-00006', 'b64318c0-ed50-4b30-93a3-607c25b714df', 'patient_qr/b64318c0-ed50-4b30-93a3-607c25b714df.svg', NULL, NULL, '2025-12-08 03:17:15', '2025-12-08 03:17:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_dokters`
--
ALTER TABLE `jadwal_dokters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_dokters_dokter_id_foreign` (`dokter_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasis_user_id_dibaca_created_at_index` (`user_id`,`dibaca`,`created_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembayarans_kode_tagihan_unique` (`kode_tagihan`),
  ADD KEY `pembayarans_user_id_foreign` (`user_id`);

--
-- Indexes for table `pendaftarans`
--
ALTER TABLE `pendaftarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pendaftarans_user_id_foreign` (`user_id`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rekam_medis_pendaftaran_id_foreign` (`pendaftaran_id`),
  ADD KEY `rekam_medis_dokter_id_foreign` (`dokter_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_dokters`
--
ALTER TABLE `jadwal_dokters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifikasis`
--
ALTER TABLE `notifikasis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pendaftarans`
--
ALTER TABLE `pendaftarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_dokters`
--
ALTER TABLE `jadwal_dokters`
  ADD CONSTRAINT `jadwal_dokters_dokter_id_foreign` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD CONSTRAINT `notifikasis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftarans`
--
ALTER TABLE `pendaftarans`
  ADD CONSTRAINT `pendaftarans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `rekam_medis_dokter_id_foreign` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekam_medis_pendaftaran_id_foreign` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftarans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
