<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\superadmin\Bidang;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'nama_lengkap',
        'username_email',
        'password',
        'role',
        'bidang_id',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // IMPORTANT: Override method untuk custom column
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relasi
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'bidang_id');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
}