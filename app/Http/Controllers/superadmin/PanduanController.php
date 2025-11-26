<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    /**
     * Tampilkan halaman panduan berdasarkan kategori.
     */
    public function index($category = 'informasi-umum')
    {
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
            'activeCategory' => $category
        ]);
    }
}
