<?php

namespace App\Models\superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bidang extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'bidang';
    protected $primaryKey = 'bidang_id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_bidang',
        'singkatan_bidang',
    ];

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'bidang_id', 'bidang_id');
    }
}