<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnOnPembayaransTable extends Migration
{
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('status', 50)->change(); // Ubah panjang jadi 50 karakter
        });
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('status', 10)->change(); // Kembalikan jika rollback
        });
    }
}