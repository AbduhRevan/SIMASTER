<?php

namespace App\Models\superadmin;

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

    /**
     * Relasi dengan tabel Server
     * Satu rak bisa punya banyak server
     */
    public function servers()
    {
        return $this->hasMany(Server::class, 'rak_id', 'rak_id');
    }

    /**
     * Accessor untuk menghitung total U slot terpakai
     */
    public function getTerpakaiUAttribute()
    {
        $total = 0;
        foreach($this->servers as $server) {
            if($server->u_slot) {
                $slots = explode('-', $server->u_slot);
                if(count($slots) == 2) {
                    $total += (int)$slots[1] - (int)$slots[0] + 1;
                } else {
                    $total += 1;
                }
            }
        }
        return $total;
    }

    /**
     * Accessor untuk menghitung sisa U slot
     */
    public function getSisaUAttribute()
    {
        return $this->kapasitas_u_slot - $this->terpakai_u;
    }
}