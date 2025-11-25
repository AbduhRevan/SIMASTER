<?php

namespace App\Models\infratik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\superadmin\RakServer;
use App\Models\superadmin\Bidang;
use App\Models\superadmin\Satker;
use App\Models\infratik\Website;

class Server extends Model
{
    use HasFactory;

    protected $table = 'server';
    protected $primaryKey = 'server_id';
    public $timestamps = true;

    protected $fillable = [
        'nama_server',
        'brand',
        'spesifikasi',
        'power_status',
        'rak_id',
        'u_slot',
        'bidang_id',
        'satker_id',
        'keterangan'
    ];

    // Relasi join tabel lain
    public function rak()
    {
        return $this->belongsTo(RakServer::class, 'rak_id', 'rak_id');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'bidang_id');
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class, 'satker_id', 'satker_id');
    }

    /**
     * RELASI: 1 server bisa punya banyak website
     */
    public function websites()
    {
        return $this->hasMany(Website::class, 'server_id', 'server_id');
    }
}
