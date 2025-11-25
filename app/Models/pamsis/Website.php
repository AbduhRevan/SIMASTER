<?php

namespace App\Models\pamsis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\superadmin\RakServer;
use App\Models\superadmin\Bidang;
use App\Models\superadmin\Satker;
use App\Models\pamsis\Website;

class Website extends Model
{
    use HasFactory;

    protected $table = 'website';
    protected $primaryKey = 'website_id';
    public $timestamps = true;

    protected $fillable = [
        'nama_website',
        'url',
        'bidang_id',
        'satker_id',
        'server_id', // TAMBAHKAN INI
        'status',
        'tahun_pengadaan',
        'keterangan',
    ];

    // Relasi ke Bidang
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'bidang_id');
    }

    // Relasi ke Satker
    public function satker()
    {
        return $this->belongsTo(Satker::class, 'satker_id', 'satker_id');
    }

    /**
     * RELASI DIUBAH: Website belongs to Server
     */
    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id', 'server_id');
    }
}
