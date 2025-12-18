<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DokterPendaftaranController;
use App\Http\Controllers\DokterRekamMedisController;
use App\Http\Controllers\PasienRekamMedisController;
use App\Http\Controllers\DokterJadwalController;
use App\Http\Controllers\DokterDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminDokterController;
use App\Http\Controllers\AdminPasienController;
use App\Http\Controllers\AdminResepsionisController;
use App\Http\Controllers\AdminPembayaranController;
use App\Http\Controllers\ResepsionisPasienController;
use App\Http\Controllers\PembayaranPasienController;
use App\Http\Controllers\Resepsionis\PendaftaranViewController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ResepsionisQrScanController;
use App\Http\Controllers\ResepsionisDashboardController;
use App\Http\Controllers\AdminLaporanController;

use L5Swagger\Http\Controllers\SwaggerController;

use App\Models\Pendaftaran;

/*
|--------------------------------------------------------------------------
| Swagger (L5-Swagger) – DI LUAR auth middleware (FIX config NULL)
|--------------------------------------------------------------------------
| FIX utama:
| - defaults('documentation', 'default')
| - defaults('config', gabungan konfigurasi l5-swagger)
|
| Ini mencegah error:
| Trying to access array offset on null (SwaggerController.php line 96)
*/
Route::get('/api/documentation', [SwaggerController::class, 'api'])
    ->name('l5-swagger.default.api')
    ->defaults('documentation', 'default')
    ->defaults('config', array_merge(
        config('l5-swagger.defaults', []),
        config('l5-swagger.documentations.default', [])
    ));

/*
| Route untuk file json/yaml docs (tetap di luar auth)
*/
Route::get('/docs', [SwaggerController::class, 'docs'])
    ->name('l5-swagger.default.docs')
    ->defaults('documentation', 'default')
    ->defaults('config', array_merge(
        config('l5-swagger.defaults', []),
        config('l5-swagger.documentations.default', [])
    ));

/*
|--------------------------------------------------------------------------
| Public / Landing
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Auth (Login / Register)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

/*
|--------------------------------------------------------------------------
| Semua route yang butuh login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Role Landing Pages (optional)
    |--------------------------------------------------------------------------
    */
    Route::get('/dokter', [DokterDashboardController::class, 'index'])
        ->middleware('role:dokter');

    Route::get('/pasien', fn () => view('pasien.index'))
        ->middleware('role:pasien');

    Route::get('/resepsionis', [ResepsionisDashboardController::class, 'index'])
        ->middleware('role:resepsionis');

    /*
    |--------------------------------------------------------------------------
    | ✅ FIX PENTING:
    | Route scan pasien HARUS DI LUAR role:pasien
    | Supaya dokter/resepsionis/admin bisa akses setelah scan/upload QR
    |--------------------------------------------------------------------------
    */
    Route::get('/scan/pasien/{token}', [PasienController::class, 'scan'])
        ->name('pasien.scan');

    /*
    |--------------------------------------------------------------------------
    | Pasien - Umum (hanya pasien)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:pasien')->group(function () {

        Route::get('/jadwal-dokter', [DokterJadwalController::class, 'pasienView'])->name('pasien.jadwal');
        Route::get('/profil', fn () => view('pasien.profile'));

        // Tagihan (URL utama)
        Route::get('/tagihan', [PembayaranPasienController::class, 'index'])->name('pasien.tagihan');

        // Alias FIX: supaya /pasien/tagihan tidak 404 (menu lama / link lama)
        Route::get('/pasien/tagihan', [PembayaranPasienController::class, 'index'])->name('pasien.tagihan.alias');

        /*
        |--------------------------------------------------------------------------
        | Pendaftaran Pasien
        |--------------------------------------------------------------------------
        */
        Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
        Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
        Route::get('/pendaftaran/sukses/{id}', [PendaftaranController::class, 'success'])->name('pendaftaran.success');
        Route::get('/pendaftaran/checkin/{token}', [PendaftaranController::class, 'checkin'])->name('pendaftaran.checkin');

        /*
        |--------------------------------------------------------------------------
        | Rekam Medis - Pasien
        |--------------------------------------------------------------------------
        */
        Route::get('/rekam-medis', [PasienRekamMedisController::class, 'index'])->name('pasien.rekam_medis');

        /*
        |--------------------------------------------------------------------------
        | Notifikasi - Pasien
        |--------------------------------------------------------------------------
        */
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('pasien.notifikasi');
        Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'read'])->name('pasien.notifikasi.read');
        Route::post('/notifikasi/read-all', [NotifikasiController::class, 'readAll'])->name('pasien.notifikasi.readAll');

        /*
        |--------------------------------------------------------------------------
        | Stream Bukti Pembayaran (anti 403)
        |--------------------------------------------------------------------------
        */
        Route::get('/pembayaran/bukti/{id}', [PembayaranPasienController::class, 'bukti'])
            ->name('pembayaran.bukti');

        /*
        |--------------------------------------------------------------------------
        | Upload bukti pembayaran
        |--------------------------------------------------------------------------
        */
        Route::post('/pasien/tagihan/upload/{id}', [PembayaranPasienController::class, 'uploadBukti'])
            ->name('pasien.upload.bukti');

        /*
        |--------------------------------------------------------------------------
        | Kartu Pasien
        |--------------------------------------------------------------------------
        */
        Route::get('/kartu-pasien', [PasienController::class, 'kartu'])->name('pasien.kartu');
    });

    /*
    |--------------------------------------------------------------------------
    | Dokter (hanya dokter)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:dokter')->prefix('dokter')->group(function () {

        Route::get('/jadwal', [DokterJadwalController::class, 'index'])->name('dokter.jadwal.index');
        Route::post('/jadwal', [DokterJadwalController::class, 'store'])->name('dokter.jadwal.store');
        Route::delete('/jadwal/{id}', [DokterJadwalController::class, 'destroy'])->name('dokter.jadwal.destroy');

        Route::get('/daftar-rekam-medis', [DokterRekamMedisController::class, 'index'])->name('dokter.daftar_rekam_medis');

        Route::get('/pendaftar', [DokterPendaftaranController::class, 'index'])->name('dokter.pendaftar');
        Route::get('/pendaftar/{id}', [DokterPendaftaranController::class, 'show'])->name('dokter.pendaftaran.show');
        Route::put('/pendaftar/{id}/status', [DokterPendaftaranController::class, 'updateStatus'])->name('dokter.pendaftaran.updateStatus');

        Route::get('/rekam_medis/{id}', [DokterRekamMedisController::class, 'show'])->name('dokter.rekam_medis.show');
        Route::post('/rekam_medis/{id}', [DokterRekamMedisController::class, 'store'])->name('dokter.rekam_medis.store');

        // Data pasien yang sudah “datang”
        Route::get('/pasien', function () {
            $pendaftars = Pendaftaran::where('status', Pendaftaran::STATUS_DATANG)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dokter.data_pasien', compact('pendaftars'));
        })->name('dokter.data_pasien');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin (hanya admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Dokter
        Route::get('/dokter', [AdminDokterController::class, 'index'])->name('admin.dokter.index');
        Route::get('/dokter/create', [AdminDokterController::class, 'create'])->name('admin.dokter.create');
        Route::post('/dokter/store', [AdminDokterController::class, 'store'])->name('admin.dokter.store');
        Route::get('/dokter/{id}/edit', [AdminDokterController::class, 'edit'])->name('admin.dokter.edit');
        Route::put('/dokter/{id}', [AdminDokterController::class, 'update'])->name('admin.dokter.update');
        Route::delete('/dokter/{id}', [AdminDokterController::class, 'destroy'])->name('admin.dokter.destroy');

        // Pasien
        Route::get('/pasien', [AdminPasienController::class, 'index'])->name('admin.pasien.index');
        Route::get('/pasien/create', [AdminPasienController::class, 'create'])->name('admin.pasien.create');
        Route::post('/pasien', [AdminPasienController::class, 'store'])->name('admin.pasien.store');
        Route::get('/pasien/{id}/edit', [AdminPasienController::class, 'edit'])->name('admin.pasien.edit');
        Route::put('/pasien/{id}', [AdminPasienController::class, 'update'])->name('admin.pasien.update');
        Route::delete('/pasien/{id}', [AdminPasienController::class, 'destroy'])->name('admin.pasien.destroy');

        // Resepsionis
        Route::get('/resepsionis', [AdminResepsionisController::class, 'index'])->name('admin.resepsionis.index');
        Route::get('/resepsionis/create', [AdminResepsionisController::class, 'create'])->name('admin.resepsionis.create');
        Route::post('/resepsionis', [AdminResepsionisController::class, 'store'])->name('admin.resepsionis.store');
        Route::get('/resepsionis/{id}/edit', [AdminResepsionisController::class, 'edit'])->name('admin.resepsionis.edit');
        Route::put('/resepsionis/{id}', [AdminResepsionisController::class, 'update'])->name('admin.resepsionis.update');
        Route::delete('/resepsionis/{id}', [AdminResepsionisController::class, 'destroy'])->name('admin.resepsionis.destroy');

        // Pembayaran
        Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('admin.pembayaran.index');
        Route::get('/pembayaran/create', [AdminPembayaranController::class, 'create'])->name('admin.pembayaran.create');
        Route::post('/pembayaran', [AdminPembayaranController::class, 'store'])->name('admin.pembayaran.store');

        // Konfirmasi pembayaran
        Route::get('/pembayaran/konfirmasi', [AdminPembayaranController::class, 'konfirmasi'])->name('admin.pembayaran.konfirmasi');
        Route::post('/pembayaran/konfirmasi/{id}/update', [AdminPembayaranController::class, 'updateStatus'])->name('admin.pembayaran.konfirmasi.update');

        // Laporan
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Resepsionis (hanya resepsionis)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:resepsionis')->prefix('resepsionis')->group(function () {

        Route::get('/daftar-pasien', [ResepsionisPasienController::class, 'create'])->name('resepsionis.daftar');
        Route::post('/daftar-pasien', [ResepsionisPasienController::class, 'store'])->name('resepsionis.daftar.store');

        Route::get('/pendaftaran', [PendaftaranViewController::class, 'index'])->name('resepsionis.pendaftaran.index');
        Route::get('/pendaftaran/{id}/cetak', [PendaftaranViewController::class, 'cetak'])->name('resepsionis.pendaftaran.cetak');

        Route::get('/qr-scan', [ResepsionisQrScanController::class, 'index'])->name('resepsionis.qr_scan');
        Route::post('/qr-scan', [ResepsionisQrScanController::class, 'process'])->name('resepsionis.qr_scan.process');
    });
});
