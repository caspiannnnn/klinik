<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'alamat',
        'telepon',
        'no_hp',
        'tanggal_lahir',
        'jenis_kelamin',
        'nik',
        'role',
        'spesialis',
        'no_rm',
        'qr_token',
        'qr_path',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir'     => 'date',
        'password'          => 'hashed',
    ];

    protected $appends = ['qr_url'];

    public function getQrUrlAttribute(): ?string
    {
        return $this->qr_path ? asset('storage/'.$this->qr_path) : null;
    }
}
