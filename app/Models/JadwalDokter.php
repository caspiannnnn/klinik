<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    use HasFactory;

    protected $table = 'jadwal_dokters';

    protected $fillable = [
        'dokter_id',
        'pasien_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    // supaya $item->tanggal selalu tersedia di Blade
    protected $appends = ['tanggal'];

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    /**
     * Accessor: tanggal virtual.
     * Ambil dari kolom yang tersedia (fallback berurutan).
     */
    public function getTanggalAttribute()
    {
        return $this->attributes['tanggal']
            ?? $this->attributes['tgl']
            ?? $this->attributes['hari']
            ?? $this->attributes['tanggal_konsultasi']
            ?? $this->attributes['tanggal_praktek']
            ?? $this->attributes['date']
            ?? null;
    }
}
