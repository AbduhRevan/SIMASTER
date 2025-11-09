<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satker; // panggil modelnya

class SatkerController extends Controller
{
    // Menampilkan semua data satker
    public function index()
    {
        $satker = Satker::all(); // ambil semua data satker pakai model
        return view('superadmin.satuankerja', compact('satker'));
    }

    // Menyimpan data satker baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_satker' => 'required|string|max:100',
            'singkatan_satker' => 'required|string|max:20',
        ]);

        Satker::create([
            'nama_satker' => $request->nama_satker,
            'singkatan_satker' => $request->singkatan_satker,
        ]);

        return redirect()->route('superadmin.satuankerja')
                         ->with('success', 'Satker baru berhasil ditambahkan!');
    }
}
