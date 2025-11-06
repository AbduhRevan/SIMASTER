<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $table = 'bidang';
    protected $primaryKey = 'bidang_id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_bidang',
        'singkatan_bidang',
    ];

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'bidang_id', 'bidang_id');
    }
}