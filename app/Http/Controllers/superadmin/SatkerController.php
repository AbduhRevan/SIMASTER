<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\Satker; // panggil modelnya

class SatkerController extends Controller
{
    // Menampilkan semua data satker
    public function index()
    {
        $satker = Satker::orderBy('nama_satker')->paginate(6); // ambil semua data satker pakai model
        return view('superadmin.satuankerja', compact('satker'));
    }

    // Menyimpan data satker baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_satker' => 'required|string|max:255',
            'singkatan_satker' => 'required|string|max:100',
        ]);

        Satker::create([
            'nama_satker' => $request->nama_satker,
            'singkatan_satker' => $request->singkatan_satker,
        ]);

        return redirect()->route('superadmin.satuankerja')
                         ->with('success', 'Satuan Kerja baru berhasil ditambahkan!');
    }
}