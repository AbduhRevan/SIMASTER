<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    protected $table = 'satuan_kerja'; // nama tabel
    protected $primaryKey = 'satker_id';
    protected $fillable = [
        'nama_satker',
        'singkatan_satker',
    ];
}
