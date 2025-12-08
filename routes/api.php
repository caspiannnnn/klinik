<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\PendaftaranApiController;
use App\Http\Controllers\Api\RekamMedisApiController;
use App\Http\Controllers\Api\PembayaranApiController;
use App\Http\Controllers\Api\JadwalDokterApiController;
use App\Http\Controllers\Api\NotifikasiApiController;
use App\Http\Controllers\Api\ProfileApiController;

/*
|--------------------------------------------------------------------------
| Public / unprotected endpoints
|--------------------------------------------------------------------------
*/

// Simple health check
Route::get('/health', function () {
    return response()->json([
        'status'  => 'ok',
        'app'     => config('app.name'),
        'time'    => now()->toIso8601String(),
        'version' => config('app.version', '1.0.0'),
    ]);
});

// Auth (token-based API, Sanctum)
Route::post('/auth/login',    [AuthApiController::class, 'login']);
Route::post('/auth/register', [AuthApiController::class, 'registerPasien']);
// kalau mau aktivasi OTP / verifikasi email, bisa ditambah di sini

/*
|--------------------------------------------------------------------------
| Protected endpoints (wajib auth:sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Default route dari Laravel install:api
    Route::get('/user', function (Request $request) {
        return $request->user()->load('pendaftarans');
    });

    // Auth / session
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/auth/me',      [AuthApiController::class, 'me']);

    /*
    |----------------------------------------------------------------------
    | Profile / data user (pasien, dokter, resepsionis, admin)
    |----------------------------------------------------------------------
    */

    Route::get('/profile',           [ProfileApiController::class, 'show']);
    Route::put('/profile',           [ProfileApiController::class, 'update']);
    Route::post('/profile/password', [ProfileApiController::class, 'updatePassword']);

    /*
    |----------------------------------------------------------------------
    | Pendaftaran / antrian
    | Model: App\Models\Pendaftaran
    |----------------------------------------------------------------------
    */

    // List pendaftaran (dengan filter optional: ?status=menunggu&hari_ini=1&per_page=20)
    Route::get('/pendaftarans', [PendaftaranApiController::class, 'index']);

    // Buat pendaftaran baru (pasien daftar berobat)
    Route::post('/pendaftarans', [PendaftaranApiController::class, 'store']);

    // Detail 1 pendaftaran
    Route::get('/pendaftarans/{pendaftaran}', [PendaftaranApiController::class, 'show']);

    // Update pendaftaran (misalnya ubah status, nomor_urut, dsb.)
    Route::put('/pendaftarans/{pendaftaran}',   [PendaftaranApiController::class, 'update']);
    Route::patch('/pendaftarans/{pendaftaran}', [PendaftaranApiController::class, 'update']);

    // Hapus pendaftaran (jika memang diizinkan)
    Route::delete('/pendaftarans/{pendaftaran}', [PendaftaranApiController::class, 'destroy']);

    // Khusus: pendaftaran hari ini
    Route::get('/pendaftarans/hari-ini', [PendaftaranApiController::class, 'today']);

    // Khusus: check-in via QR / token
    Route::post('/pendaftarans/{pendaftaran}/checkin', [PendaftaranApiController::class, 'checkin']);

    /*
    |----------------------------------------------------------------------
    | Rekam Medis
    | Model: App\Models\RekamMedis (tabel: rekam_medis)
    |----------------------------------------------------------------------
    */

    // List rekam medis (bisa pakai filter ?pasien_id=...&dokter_id=...)
    Route::get('/rekam-medis', [RekamMedisApiController::class, 'index']);

    // Buat rekam medis baru untuk pendaftaran tertentu
    Route::post('/rekam-medis', [RekamMedisApiController::class, 'store']);

    // Detail rekam medis
    Route::get('/rekam-medis/{rekam_medi}', [RekamMedisApiController::class, 'show']);

    // Update rekam medis
    Route::put('/rekam-medis/{rekam_medi}',   [RekamMedisApiController::class, 'update']);
    Route::patch('/rekam-medis/{rekam_medi}', [RekamMedisApiController::class, 'update']);

    // Hapus rekam medis
    Route::delete('/rekam-medis/{rekam_medi}', [RekamMedisApiController::class, 'destroy']);

    // Rekam medis per pasien
    Route::get('/rekam-medis/pasien/{user}', [RekamMedisApiController::class, 'byPatient']);

    /*
    |----------------------------------------------------------------------
    | Pembayaran
    | Model: App\Models\Pembayaran
    |----------------------------------------------------------------------
    */

    // List pembayaran (bisa difilter: ?status=belum_bayar|lunas, ?user_id=)
    Route::get('/pembayarans', [PembayaranApiController::class, 'index']);

    // Buat tagihan / pembayaran
    Route::post('/pembayarans', [PembayaranApiController::class, 'store']);

    // Detail pembayaran
    Route::get('/pembayarans/{pembayaran}', [PembayaranApiController::class, 'show']);

    // Update status pembayaran (misalnya konfirmasi lunas)
    Route::put('/pembayarans/{pembayaran}',   [PembayaranApiController::class, 'update']);
    Route::patch('/pembayarans/{pembayaran}', [PembayaranApiController::class, 'update']);

    // Upload bukti pembayaran (untuk pasien)
    Route::post('/pembayarans/{pembayaran}/upload-bukti', [PembayaranApiController::class, 'uploadBukti']);

    // (opsional) list pembayaran per pasien
    Route::get('/pembayarans/pasien/{user}', [PembayaranApiController::class, 'byPatient']);

    /*
    |----------------------------------------------------------------------
    | Jadwal Dokter
    | Model: App\Models\JadwalDokter
    |----------------------------------------------------------------------
    */

    // List jadwal dokter
    Route::get('/jadwal-dokter', [JadwalDokterApiController::class, 'index']);

    // Tambah jadwal
    Route::post('/jadwal-dokter', [JadwalDokterApiController::class, 'store']);

    // Detail satu jadwal
    Route::get('/jadwal-dokter/{jadwal_dokter}', [JadwalDokterApiController::class, 'show']);

    // Update jadwal
    Route::put('/jadwal-dokter/{jadwal_dokter}',   [JadwalDokterApiController::class, 'update']);
    Route::patch('/jadwal-dokter/{jadwal_dokter}', [JadwalDokterApiController::class, 'update']);

    // Hapus jadwal
    Route::delete('/jadwal-dokter/{jadwal_dokter}', [JadwalDokterApiController::class, 'destroy']);

    // Jadwal per dokter
    Route::get('/jadwal-dokter/dokter/{dokter}', [JadwalDokterApiController::class, 'byDoctor']);

    /*
    |----------------------------------------------------------------------
    | Notifikasi
    | Model: App\Models\Notifikasi
    |----------------------------------------------------------------------
    */

    // List notifikasi milik user login (paginate)
    Route::get('/notifikasis', [NotifikasiApiController::class, 'index']);

    // Detail satu notifikasi
    Route::get('/notifikasis/{notifikasi}', [NotifikasiApiController::class, 'show']);

    // Hapus notifikasi
    Route::delete('/notifikasis/{notifikasi}', [NotifikasiApiController::class, 'destroy']);

    // Tandai satu notifikasi sudah dibaca
    Route::post('/notifikasis/{notifikasi}/mark-read', [NotifikasiApiController::class, 'markRead']);

    // Tandai semua notifikasi user sebagai sudah dibaca
    Route::post('/notifikasis/mark-all-read', [NotifikasiApiController::class, 'markAllRead']);

    // Hitung notifikasi belum dibaca
    Route::get('/notifikasis/unread-count', [NotifikasiApiController::class, 'unreadCount']);
});
