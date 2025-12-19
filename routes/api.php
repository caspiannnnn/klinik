<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\ProfileApiController;
use App\Http\Controllers\API\PendaftaranApiController;
use App\Http\Controllers\API\RekamMedisApiController;
use App\Http\Controllers\API\JadwalDokterApiController;
use App\Http\Controllers\API\NotifikasiApiController;
use App\Http\Controllers\API\PembayaranApiController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'time' => now()->toIso8601String(),
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function () {

    // ===== Profile =====
    Route::get('profile', [ProfileApiController::class, 'show']);
    Route::put('profile', [ProfileApiController::class, 'update']);

    // ===== Pendaftaran =====
    Route::get('pendaftarans/user/{user_id}', [PendaftaranApiController::class, 'byUser']);
    Route::post('pendaftarans/{pendaftaran}/upload-bukti', [PendaftaranApiController::class, 'uploadBukti']);
    Route::apiResource('pendaftarans', PendaftaranApiController::class);

    // ===== Rekam Medis =====
    Route::get('rekam-medis/pasien/{pasien_id}', [RekamMedisApiController::class, 'byPasien']);
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
