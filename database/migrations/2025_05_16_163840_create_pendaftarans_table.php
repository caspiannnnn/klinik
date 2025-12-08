<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin', 20);

            // ✅ Simpan keduanya
            $table->string('no_hp', 20);
            $table->string('nik', 16);

            $table->text('keluhan');
            $table->string('status')->default('menunggu');

            $table->unsignedInteger('nomor_urut')->nullable();
            $table->string('kode_antrian', 10)->nullable();

            $table->uuid('qr_token')->nullable();
            $table->string('qr_path')->nullable();
            $table->timestamp('checkin_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
