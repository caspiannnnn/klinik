<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DATANG   = 'datang';
    public const STATUS_SELESAI  = 'selesai';

    protected $fillable = [
        'user_id',
        'nama',
        'jenis_kelamin',
        'no_hp',
        'nik',
        'keluhan',
        'status',
        'tanggal_lahir',
        'nomor_urut',
        'kode_antrian',
        'qr_token',
        'qr_path',
        'checkin_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'checkin_at'    => 'datetime',
    ];

    protected $appends = ['qr_url'];

    public function getQrUrlAttribute(): ?string
    {
        return $this->qr_path ? asset('storage/'.$this->qr_path) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }
}
