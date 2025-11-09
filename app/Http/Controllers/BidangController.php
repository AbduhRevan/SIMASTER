<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidangController extends Controller
{
    // Menampilkan semua data bidang
    public function index()
    {
        $bidang = DB::table('bidang')->get();
        return view('superadmin.bidang', compact('bidang'));
    }

    // Menyimpan data bidang baru
    public function store(Request $request)
    {
        // Validasi input dulu
        $request->validate([
            'nama_bidang' => 'required|string|max:100',
            'singkatan_bidang' => 'required|string|max:20',
        ]);

        // Simpan ke database
        DB::table('bidang')->insert([
            'nama_bidang' => $request->nama_bidang,
            'singkatan_bidang' => $request->singkatan_bidang,
        ]);

        // Balik ke halaman bidang dengan pesan sukses
        return redirect()->route('superadmin.bidang')->with('success', 'Bidang baru berhasil ditambahkan!');
    }
}
