<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $table = 'website';
    protected $primaryKey = 'website_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_website',
        'url',
        'bidang_id',
        'satker_id',
        'status',
        'tahun_pengadaan',
        'keterangan',
    ];

    // Relasi ke Bidang
    public function bidang() {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'bidang_id');
    }

    // Relasi ke Satker
    public function satker() {
        return $this->belongsTo(Satker::class, 'satker_id', 'satker_id');
    }
}
