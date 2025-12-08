<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pasien
            $table->string('kode_tagihan')->unique();
            $table->decimal('jumlah', 10, 2);
            $table->enum('status', ['belum dibayar', 'menunggu konfirmasi', 'lunas'])->default('belum dibayar');
            $table->string('bukti_pembayaran')->nullable(); // path bukti transfer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};