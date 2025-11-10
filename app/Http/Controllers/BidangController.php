<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;

class BidangController extends Controller
{
    // Menampilkan semua data bidang
    public function index()
    {
        $bidang = Bidang::all(); // ambil semua data pakai model
        return view('superadmin.bidang', compact('bidang'));
    }

    // Menyimpan data bidang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'singkatan_bidang' => 'required|string|max:100',
        ]);

        // Simpan pakai model 
        Bidang::create([
            'nama_bidang' => $request->nama_bidang,
            'singkatan_bidang' => $request->singkatan_bidang,
        ]);

        return redirect()->route('superadmin.bidang')
                         ->with('success', 'Bidang baru berhasil ditambahkan!');
    }

    // Mengupdate data bidang
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'singkatan_bidang' => 'required|string|max:100',
        ]); 

        $bidang = Bidang::findOrFail($id);
        $bidang->update([
            'nama_bidang' => $request->nama_bidang,
            'singkatan_bidang' => $request->singkatan_bidang,
        ]);

        return redirect()->route('superadmin.bidang')->with('success', 'Data bidang berhasil diperbarui!');
    }

    // Menghapus data bidang
    public function destroy($id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();

        return redirect()->route('superadmin.bidang')->with('success', 'Data bidang berhasil dihapus!');
    }
}
