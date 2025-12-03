<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// HAPUS: use Illuminate\Database\Eloquent\SoftDeletes;

class Satker extends Model
{
    use HasFactory;
    // HAPUS: use HasFactory, SoftDeletes;

    protected $table = 'satuan_kerja';
    protected $primaryKey = 'satker_id';

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'nama_satker',
        'singkatan_satker',
    ];

    /**
     * Relasi dengan tabel lain jika ada
     * Contoh: relasi dengan pengguna atau website
     */
    // public function pengguna()
    // {
    //     return $this->hasMany(\App\Models\Pengguna::class, 'satker_id', 'satker_id');
    // }
}
