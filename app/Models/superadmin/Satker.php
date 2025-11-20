<?php

namespace App\Models\superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satker extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'satuan_kerja';
    protected $primaryKey = 'satker_id';

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'nama_satker',
        'singkatan_satker',
    ];
}
