<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'aksi',
        'entitas_diubah',
        'deskripsi',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'user_id');
    }
}