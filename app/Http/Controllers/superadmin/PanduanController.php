<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanduanController extends Controller
{
    /**
     * Tampilkan halaman panduan berdasarkan kategori.
     */
    public function index($category = 'informasi-umum')
    {
        $user = Auth::user();

        // Tentukan layout berdasarkan role user
        $layout = match ($user->role) {
            'superadmin' => 'layouts.app',
            'banglola'   => 'layouts.banglola',
            'pamsis'     => 'layouts.pamsis',
            'infratik'   => 'layouts.infratik',
            'tatausaha'  => 'layouts.tatausaha',
            default      => 'layouts.app',
        };

        // daftar kategori valid
        $allowedCategories = [
            'informasi-umum',
            'data-master',
            'manajemen-aset',
            'manajemen-sistem',
            'manajemen-akun'
        ];

        // jika kategori ga valid → fallback
        if (!in_array($category, $allowedCategories)) {
            $category = 'informasi-umum';
        }

        return view('profil.panduan_pengguna', [
            'activeCategory' => $category,
            'layout' => $layout
        ]);
    }
}
