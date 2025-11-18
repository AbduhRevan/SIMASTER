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
        'waktu_aksi'
    ];

    protected $casts = [
        'waktu_aksi' => 'datetime'
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            if (empty($log->waktu_aksi)) {
                $log->waktu_aksi = now();
            }
        });
    }

    public static function log($aksi, $entitas, $deskripsi, $userId = null)
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'aksi' => $aksi,
            'entitas_diubah' => $entitas,
            'deskripsi' => $deskripsi,
            'waktu_aksi' => now()
        ]);
    }
}
