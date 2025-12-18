<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\PendaftaranApiController;
use App\Http\Controllers\Api\RekamMedisApiController;
use App\Http\Controllers\Api\JadwalDokterApiController;
use App\Http\Controllers\Api\NotifikasiApiController;
use App\Http\Controllers\Api\PembayaranApiController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'time' => now()->toIso8601String(),
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'registerPasien']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthApiController::class, 'me']);
        Route::post('/logout', [AuthApiController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {

    // ===== Profile =====
    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::put('/profile', [ProfileApiController::class, 'update']);
    Route::post('/profile/password', [ProfileApiController::class, 'updatePassword']);

    // ===== Pendaftaran (konsisten: /api/pendaftarans) =====
    Route::get('pendaftarans/hari-ini', [PendaftaranApiController::class, 'today']);
    Route::post('pendaftarans/{pendaftaran}/checkin', [PendaftaranApiController::class, 'checkin']);
    Route::apiResource('pendaftarans', PendaftaranApiController::class);

    // ===== Rekam Medis =====
    Route::get('rekam-medis/pasien/{user_id}', [RekamMedisApiController::class, 'byPatient']);
    Route::apiResource('rekam-medis', RekamMedisApiController::class);

    // ===== Jadwal Dokter =====
    Route::get('jadwal-dokter/dokter/{dokter_id}', [JadwalDokterApiController::class, 'byDoctor']);
    Route::apiResource('jadwal-dokter', JadwalDokterApiController::class);

    // ===== Notifikasi =====
    Route::get('notifikasis/unread-count', [NotifikasiApiController::class, 'unreadCount']);
    Route::post('notifikasis/mark-all-read', [NotifikasiApiController::class, 'markAllRead']);
    Route::post('notifikasis/{notifikasi}/mark-read', [NotifikasiApiController::class, 'markRead']);
    Route::apiResource('notifikasis', NotifikasiApiController::class)->only(['index', 'show', 'destroy']);

    // ===== Pembayaran =====
    Route::get('pembayarans/pasien/{user_id}', [PembayaranApiController::class, 'byPatient']);
    Route::post('pembayarans/{pembayaran}/upload-bukti', [PembayaranApiController::class, 'uploadBukti']);
    Route::apiResource('pembayarans', PembayaranApiController::class);
});
