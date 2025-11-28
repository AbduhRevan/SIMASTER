<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPanduan;
use App\Models\ItemPanduan;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    /**
     * Tampilkan halaman panduan berdasarkan kategori.
     */
    public function index($slug = null)
    {
        // Ambil semua kategori aktif
        $kategoriList = KategoriPanduan::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->get();

        // Handle jika tidak ada kategori
        if ($kategoriList->isEmpty()) {
            return view('profil.panduan_pengguna', [
                'kategoriList' => collect(),
                'kategoriAktif' => null,
                'items' => collect()
            ]);
        }

        // Jika slug tidak ada, ambil kategori pertama
        if (!$slug) {
            $kategoriAktif = $kategoriList->first();
        } else {
            $kategoriAktif = KategoriPanduan::where('slug', $slug)
                ->where('is_active', true)
                ->first();

            // Jika tidak ditemukan, fallback ke kategori pertama
            if (!$kategoriAktif) {
                $kategoriAktif = $kategoriList->first();
            }
        }

        // Ambil item panduan berdasarkan kategori
        $items = ItemPanduan::where('kategori_panduan_id', $kategoriAktif->id)
            ->where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->get();

        return view('profil.panduan_pengguna', [
            'kategoriList' => $kategoriList,
            'kategoriAktif' => $kategoriAktif,
            'items' => $items
        ]);
    }
}
