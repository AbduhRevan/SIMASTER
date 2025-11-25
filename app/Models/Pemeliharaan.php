<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\superadmin\Server;
use App\Models\superadmin\Website;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pemeliharaan';
    protected $primaryKey = 'pemeliharaan_id';
    public $timestamps = true;

    protected $fillable = [
        'server_id',
        'website_id',
        'tanggal_pemeliharaan',
        'status_pemeliharaan',
        'status_sebelumnya',
        'tanggal_selesai_aktual',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pemeliharaan' => 'date',
        'tanggal_selesai_aktual' => 'datetime',
    ];

    /**
     * Relasi ke tabel Server
     */
    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id', 'server_id');
    }

    /**
     * Relasi ke tabel Website
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id', 'website_id');
    }

    /**
     * Accessor untuk mendapatkan nama asset
     */
    public function getAssetNameAttribute()
    {
        if ($this->server_id) {
            return $this->server->nama_server ?? '-';
        } elseif ($this->website_id) {
            return $this->website->nama_website ?? '-';
        }
        return '-';
    }

    /**
     * Accessor untuk mendapatkan jenis asset
     */
    public function getAssetTypeAttribute()
    {
        if ($this->server_id) {
            return 'server';
        } elseif ($this->website_id) {
            return 'website';
        }
        return null;
    }

    /**
     * Cek apakah pemeliharaan dapat dimulai
     */
    public function canStart()
    {
        return $this->status_pemeliharaan === 'dijadwalkan';
    }

    /**
     * Cek apakah pemeliharaan dapat diselesaikan
     */
    public function canFinish()
    {
        return $this->status_pemeliharaan === 'berlangsung';
    }

    /**
     * Cek apakah pemeliharaan dapat dibatalkan
     */
    public function canCancel()
    {
        return in_array($this->status_pemeliharaan, ['dijadwalkan', 'berlangsung']);
    }
}
