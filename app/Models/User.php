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
        'password',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'nik',
<<<<<<< HEAD
=======

        // ✅ FIX: kolom ini ada di migration & dipakai di register
        'alamat',
        'telepon',

        // (opsional: kalau nanti kamu tambahkan di migration user)
        // 'no_rm',
        // 'qr_path',
>>>>>>> af9d94b (feat: update clinic app (roles, API, swagger, views))
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
<<<<<<< HEAD
        'tanggal_lahir'     => 'date',   // ✅ TAMBAH INI
=======
        'tanggal_lahir'     => 'date',
>>>>>>> af9d94b (feat: update clinic app (roles, API, swagger, views))
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
