<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakServer extends Model
{
    use HasFactory;

    protected $table = 'rak_server'; 
    protected $primaryKey = 'rak_id'; 
    public $timestamps = false;

    protected $fillable = [
        'nomor_rak',
        'ruangan',
        'kapasitas_u_slot',
        'keterangan',
    ];
}
