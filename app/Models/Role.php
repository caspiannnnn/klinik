<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Sesuaikan dengan nama tabel di database (biasanya 'roles')
    protected $table = 'roles';

    protected $fillable = [
        'name', // contoh: 'admin', 'dokter', 'resepsionis', 'pasien'
    ];

    public $timestamps = false; // kalau tabel roles tidak pakai timestamps, kalau pakai → ubah ke true

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
