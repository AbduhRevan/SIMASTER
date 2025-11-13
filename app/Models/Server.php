<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $table = 'server';
    protected $primaryKey = 'server_id';
    public $timestamps = true; 

    protected $fillable = [
        'nama_server', 'brand', 'spesifikasi', 'power_status',
        'rak_id', 'u_slot', 'bidang_id', 'satker_id', 'website_id', 'keterangan'
    ];

    // Relasi join tabel lain
    public function rak() {
        return $this->belongsTo(RakServer::class, 'rak_id', 'rak_id');
    }

    public function bidang() {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'bidang_id');
    }

    public function satker() {
        return $this->belongsTo(Satker::class, 'satker_id', 'satker_id');
    }

    public function website() {
        return $this->belongsTo(Website::class, 'website_id', 'website_id');
    }
}
