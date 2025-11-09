<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatkerController extends Controller
{
    // Menampilkan semua data satker
    public function index()
    {
        $satker = DB::table('satuan_kerja')->get();
        return view('superadmin.satuankerja', compact('satker'));
    }

    // Menyimpan data satker baru
    public function store(Request $request)
    {
        // Validasi input dulu
        $request->validate([
            'nama_satker' => 'required|string|max:100',
            'singkatan_satker' => 'required|string|max:20',
        ]);

        // Simpan ke database
        DB::table('satuan_kerja')->insert([
            'nama_satker' => $request->nama_satker,
            'singkatan_satker' => $request->singkatan_satker,
        ]);

        // Balik ke halaman bidang dengan pesan sukses
        return redirect()->route('superadmin.satuankerja')->with('success', 'Satker baru berhasil ditambahkan!');
    }
}
