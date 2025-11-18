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

    // Mengupdate data satker
public function update(Request $request, $id)
{
    $request->validate([
        'nama_satker' => 'required|string|max:255',
        'singkatan_satker' => 'required|string|max:100',
    ]);

    $satker = Satker::findOrFail($id);
    $satker->update([
        'nama_satker' => $request->nama_satker,
        'singkatan_satker' => $request->singkatan_satker,
    ]);

    return redirect()
        ->route('superadmin.satuankerja')
        ->with('success', 'Data Satuan Kerja berhasil diperbarui!');
}
    
     // Soft delete 
    public function softDelete($id)
    {
        $satker = Satker::findOrFail($id);
        $satker->delete(); // pastikan model pakai SoftDeletes
        return redirect()
            ->route('superadmin.satuankerja')
            ->with('success', 'Data Satuan Kerja berhasil dihapus sementara!');
    }
}