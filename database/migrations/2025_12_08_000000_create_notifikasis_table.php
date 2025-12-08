<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // penerima notif (pasien)
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe')->nullable(); // misal: pendaftaran, rekam_medis, pembayaran
            $table->string('link')->nullable(); // optional: link halaman terkait
            $table->boolean('dibaca')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'dibaca', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
