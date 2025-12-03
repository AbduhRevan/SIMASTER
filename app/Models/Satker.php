<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    protected $table = 'satuan_kerja';
    protected $primaryKey = 'satker_id';

    // Disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'nama_satker',
        'singkatan_satker',
    ];

    /**
     * Relasi dengan tabel Pengguna
     * Uncomment jika ada relasi
     */
    // public function pengguna()
    // {
    // return $this->hasMany(\App\Models\Pengguna::class, 'satker_id', 'satker_id');
    // }

    /**
 * Relasi dengan tabel Website
 * Uncomment jika ada relasi
 */
    public function websites()
    {
return $this->hasMany(\App\Models\Website::class, 'satker_id', 'satker_id');
    }
}
