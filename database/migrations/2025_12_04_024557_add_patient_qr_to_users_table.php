<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ Tambah hanya kalau kolom belum ada (anti nabrak)
            if (!Schema::hasColumn('users', 'no_rm')) {
                $table->string('no_rm', 30)->nullable()->after('role');
            }

            if (!Schema::hasColumn('users', 'qr_token')) {
                $table->string('qr_token')->nullable()->after('no_rm');
            }

            if (!Schema::hasColumn('users', 'qr_path')) {
                $table->string('qr_path')->nullable()->after('qr_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // dropColumn aman kalau kolom ada
            if (Schema::hasColumn('users', 'qr_path')) {
                $table->dropColumn('qr_path');
            }
            if (Schema::hasColumn('users', 'qr_token')) {
                $table->dropColumn('qr_token');
            }
            if (Schema::hasColumn('users', 'no_rm')) {
                $table->dropColumn('no_rm');
            }
        });
    }
};
