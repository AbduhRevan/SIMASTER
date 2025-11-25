<?php

namespace App\Models\superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\banglola\Server;

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
     * Hitung total U slot terpakai
     */
    public function getTerpakaiUAttribute()
    {
        return $this->calculateUsedSlots();
    }

    /**
     * Hitung sisa U slot
     */
    public function getSisaUAttribute()
    {
        return $this->kapasitas_u_slot - $this->terpakai_u;
    }

    /**
     * Hitung total U slot yang terpakai
     */
    private function calculateUsedSlots()
    {
        $total = 0;
        foreach ($this->servers as $server) {
            if ($server->u_slot) {
                $slots = explode('-', $server->u_slot);
                if (count($slots) == 2) {
                    $total += (int)$slots[1] - (int)$slots[0] + 1;
                } else {
                    $total += 1;
                }
            }
        }
        return $total;
    }

    /**
     * Dapatkan array slot yang sudah terpakai
     * Return format: [1, 2, 3, 5, 6, ...]
     */
    public function getOccupiedSlots()
    {
        $occupied = [];

        foreach ($this->servers as $server) {
            if ($server->u_slot) {
                $slots = explode('-', $server->u_slot);

                if (count($slots) == 2) {
                    // Range slot (misal: "1-4")
                    $start = (int)$slots[0];
                    $end = (int)$slots[1];

                    for ($i = $start; $i <= $end; $i++) {
                        $occupied[] = $i;
                    }
                } else {
                    // Single slot
                    $occupied[] = (int)$slots[0];
                }
            }
        }

        return array_unique($occupied);
    }

    /**
     * Dapatkan array slot yang masih kosong
     * Return format: [4, 7, 8, 9, ...]
     */
    public function getAvailableSlots()
    {
        $occupied = $this->getOccupiedSlots();
        $available = [];

        for ($i = 1; $i <= $this->kapasitas_u_slot; $i++) {
            if (!in_array($i, $occupied)) {
                $available[] = $i;
            }
        }

        return $available;
    }

    /**
     * Cek apakah range slot tersedia
     */
    public function isSlotRangeAvailable($start, $end, $excludeServerId = null)
    {
        $occupied = [];

        foreach ($this->servers as $server) {
            // Skip server yang sedang di-edit
            if ($excludeServerId && $server->server_id == $excludeServerId) {
                continue;
            }

            if ($server->u_slot) {
                $slots = explode('-', $server->u_slot);

                if (count($slots) == 2) {
                    $s = (int)$slots[0];
                    $e = (int)$slots[1];

                    for ($i = $s; $i <= $e; $i++) {
                        $occupied[] = $i;
                    }
                } else {
                    $occupied[] = (int)$slots[0];
                }
            }
        }

        // Cek apakah ada konflik
        for ($i = $start; $i <= $end; $i++) {
            if (in_array($i, $occupied)) {
                return false;
            }
        }

        return true;
    }
}
