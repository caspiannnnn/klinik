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
        'dokter_id',
        'diterima_oleh_dokter_id',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'nik',
        'keluhan',
        'tanggal_kunjungan',
        'jam_kunjungan',
        'spesialis',
        'status',
        'nomor_urut',
        'kode_antrian',
        'qr_token',
        'qr_path',
        'checkin_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_kunjungan' => 'date',
        'checkin_at'    => 'datetime',
    ];

    protected $appends = ['qr_url'];

    public function getQrUrlAttribute(): ?string
    {
        return $this->qr_path ? asset('storage/' . $this->qr_path) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function diterimaOlehDokter()
    {
        return $this->belongsTo(User::class, 'diterima_oleh_dokter_id');
    }

    // ✅ relasi ke rekam medis
    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'pendaftaran_id');
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    /**
     * Hitung jumlah pasien untuk dokter pada tanggal tertentu.
     * Dipakai untuk limit maksimal 5 pasien / hari.
     */
    public static function countForDoctorOnDate(int $dokterId, string $dateYmd): int
    {
        return (int) static::query()
            ->where('dokter_id', $dokterId)
            ->whereDate('tanggal_kunjungan', $dateYmd)
            ->whereNotIn('status', ['ditolak'])
            ->count();
    }

    /**
     * Buat nomor antrian berikutnya untuk dokter + tanggal.
     * Kembalian: [nomor_urut, kode_antrian]
     */
    public static function generateQueueForDoctorAndDate(int $dokterId, string $dateYmd): array
    {
        // Format kode: A-001, A-002, ... per dokter+hari
        // Prefix sederhana: huruf pertama nama dokter (fallback 'A')
        $dokter = User::query()->find($dokterId);
        $prefix = 'A';
        if ($dokter && is_string($dokter->name) && $dokter->name !== '') {
            $prefix = strtoupper(substr(trim($dokter->name), 0, 1));
        }

        $last = static::query()
            ->where('dokter_id', $dokterId)
            ->whereDate('tanggal_kunjungan', $dateYmd)
            ->max('nomor_urut');

        $next = ((int) $last) + 1;
        $kode = $prefix . '-' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);

        return [$next, $kode];
    }
}
