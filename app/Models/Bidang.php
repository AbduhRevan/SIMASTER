<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengguna; 

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';
    protected $primaryKey = 'bidang_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_bidang',
        'singkatan_bidang',
    ];

    /**
     * Relasi dengan tabel Pengguna
     * Satu bidang bisa punya banyak pengguna
     */
    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'bidang_id', 'bidang_id');
    }
}
