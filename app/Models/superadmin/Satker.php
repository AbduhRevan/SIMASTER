<?php

namespace App\Models\superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satker extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'satuan_kerja'; // nama tabel
    protected $primaryKey = 'satker_id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_satker',
        'singkatan_satker',
    ];

    protected $dates = ['deleted_at'];
}