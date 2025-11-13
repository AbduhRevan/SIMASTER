<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\Bidang;

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

    // Menghapus data bidang (soft delete)
    public function softDelete($id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();

        return redirect()->route('superadmin.bidang')->with('success', 'Data bidang berhasil dipindahkan ke arsip sementara.');
    }

    // Halaman Arsip
    // Tampilkan halaman arsip
    public function arsip()
    {
        $bidangTerhapus = Bidang::onlyTrashed()->get(); // ambil semua data pakai model
        return view('superadmin.arsip', compact('bidangTerhapus'));
    }

    // Mengembalikan data bidang yang sudah dihapus
    public function restore($id)
    {
        $bidang = Bidang::withTrashed()->findOrFail($id);
        $bidang->restore();
        return redirect()->route('superadmin.arsip')->with('success', 'Data bidang berhasil dipulihkan.');
    }

    // Hapus Permanen
    public function forceDelete($id)
    {
        $bidang = Bidang::withTrashed()->findOrFail($id);
        $bidang->forceDelete();
        return redirect()->route('superadmin.arsip')->with('success', 'Data bidang berhasil dihapus permanen.');
    }
}
