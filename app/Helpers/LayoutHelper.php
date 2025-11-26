<?php

namespace App\Helpers;

class LayoutHelper
{
    public static function getUserLayout()
    {
        $user = auth()->user();
        
        if (!$user) {
            return 'layouts.app'; // default jika belum login
        }

        // Mapping role ke layout
        $layoutMap = [
            'superadmin' => 'layouts.app',
            'banglola' => 'layouts.banglola',
            'pamsis' => 'layouts.pamsis',
            'infratik' => 'layouts.infratik',
            'tatausaha' => 'layouts.tatausaha',
        ];

        return $layoutMap[$user->role] ?? 'layouts.app';
    }
}