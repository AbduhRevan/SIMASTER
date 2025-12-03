<?php

namespace App\Helpers;

class LayoutHelper
{
    /**
     * Get layout berdasarkan role user
     * 
     * @return string
     */
    public static function getUserLayout()
    {
        $role = auth()->user()->role ?? 'guest';

        // Daftar admin bidang yang menggunakan layout app
        $adminBidang = ['banglola', 'pamsis', 'infratik', 'tatausaha'];

        // Superadmin menggunakan layout app
        if ($role === 'superadmin') {
            return 'layouts.app';
        }

        // Admin bidang juga menggunakan layout app (sama seperti superadmin)
        if (in_array($role, $adminBidang)) {
            return 'layouts.app';
        }

        // Pimpinan menggunakan layout app
        if ($role === 'pimpinan') {
            return 'layouts.app';
        }

        // Default fallback ke layout app
        return 'layouts.app';
    }
}
