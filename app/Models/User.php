<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// IMPORT RELASI MODEL
use App\Models\Role;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Resepsionis;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'spesialis', // ✅ FIX: agar spesialis tersimpan
        'password',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'nik',

        'alamat',
        'telepon',

        // (opsional bila ada di migration nanti)
        // 'no_rm',
        // 'qr_path',
        // 'qr_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'tanggal_lahir'     => 'date',
    ];

    protected $appends = [
        'qr_url',
    ];

    public function getQrUrlAttribute(): ?string
    {
        return $this->qr_path ? asset('storage/' . $this->qr_path) : null;
    }

    // ===== RELASI =====

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'user_id');
    }

    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'user_id');
    }

    public function resepsionis()
    {
        return $this->hasOne(Resepsionis::class, 'user_id');
    }
}
