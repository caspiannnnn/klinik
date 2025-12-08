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
       Schema::create('jadwal_dokters', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('dokter_id');
    $table->string('hari');
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->timestamps();

    $table->foreign('dokter_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_dokters');
    }
};