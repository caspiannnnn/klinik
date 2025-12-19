<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Jadwal kunjungan
            $table->date('tanggal_kunjungan')->nullable()->after('keluhan');
            $table->time('jam_kunjungan')->nullable()->after('tanggal_kunjungan');

            // Filter spesialis (disalin ke setiap pendaftaran)
            $table->string('spesialis', 255)->nullable()->after('jam_kunjungan');

            // Dokter yang dipilih (dan/atau ditetapkan saat diterima)
            $table->foreignId('dokter_id')->nullable()->after('user_id')
                ->constrained('users')->nullOnDelete();

            // Informasi "diterima oleh" (dokter yang menekan tombol diterima)
            $table->foreignId('diterima_oleh_dokter_id')->nullable()->after('dokter_id')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('diterima_oleh_dokter_id');
            $table->dropConstrainedForeignId('dokter_id');

            $table->dropColumn(['tanggal_kunjungan', 'jam_kunjungan', 'spesialis']);
        });
    }
};
