<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\Satker;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class SatkerController extends Controller
{
    // Menampilkan semua data satker
    public function index()
    {
        $satker = Satker::orderBy('nama_satker')->paginate(10);
        return view('superadmin.satuankerja', compact('satker'));
    }

    // Menyimpan data satker baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_satker' => 'required|string|max:150|unique:satuan_kerja,nama_satker',
            'singkatan_satker' => 'required|string|max:100',
        ], [
            'nama_satker.required' => 'Nama Satuan Kerja wajib diisi.',
            'nama_satker.unique' => 'Nama Satuan Kerja sudah terdaftar.',
            'singkatan_satker.required' => 'Singkatan wajib diisi.',
        ]);

        $satker = Satker::create([
            'nama_satker' => $request->nama_satker,
            'singkatan_satker' => $request->singkatan_satker,
        ]);

        // TAMBAHKAN LOG CREATE
        LogAktivitas::log(
            'CREATE',
            'satuan_kerja',
            "Menambahkan satuan kerja baru: {$satker->nama_satker} ({$satker->singkatan_satker})",
            Auth::id()
        );

        return redirect()
            ->route('superadmin.satuankerja')
            ->with('success', 'Satuan Kerja baru berhasil ditambahkan!');
    }

    // Fitur Search
    public function search(Request $request)
{
    $search = $request->get('search');
    
    $satker = Satker::where('nama_satker', 'LIKE', "%{$search}%")
        ->orWhere('singkatan_satker', 'LIKE', "%{$search}%")
        ->orderBy('nama_satker', 'asc')
        ->get();
    
    $total = Satker::count();
    
    return response()->json([
        'success' => true,
        'data' => $satker,
        'total' => $total,
        'found' => $satker->count()
    ]);
}


    // Mengupdate data satker
    public function update(Request $request, $id)
    {
        $satker = Satker::findOrFail($id);

        // Simpan data lama untuk log
        $namaLama = $satker->nama_satker;
        $singkatanLama = $satker->singkatan_satker;

        $request->validate([
            'nama_satker' => 'required|string|max:150|unique:satuan_kerja,nama_satker,' . $id . ',satker_id',
            'singkatan_satker' => 'required|string|max:100',
        ], [
            'nama_satker.required' => 'Nama Satuan Kerja wajib diisi.',
            'nama_satker.unique' => 'Nama Satuan Kerja sudah terdaftar.',
            'singkatan_satker.required' => 'Singkatan wajib diisi.',
        ]);

        $satker->update([
            'nama_satker' => $request->nama_satker,
            'singkatan_satker' => $request->singkatan_satker,
        ]);

        // TAMBAHKAN LOG UPDATE
        $perubahan = [];
        if ($namaLama !== $request->nama_satker) {
            $perubahan[] = "nama dari '{$namaLama}' menjadi '{$request->nama_satker}'";
        }
        if ($singkatanLama !== $request->singkatan_satker) {
            $perubahan[] = "singkatan dari '{$singkatanLama}' menjadi '{$request->singkatan_satker}'";
        }

        $deskripsiPerubahan = count($perubahan) > 0
            ? "Mengupdate satuan kerja: " . implode(', ', $perubahan)
            : "Mengupdate satuan kerja: {$request->nama_satker}";

        LogAktivitas::log(
            'UPDATE',
            'satuan_kerja',
            $deskripsiPerubahan,
            Auth::id()
        );

        return redirect()
            ->route('superadmin.satuankerja')
            ->with('success', 'Data Satuan Kerja berhasil diperbarui!');
    }

    // Menghapus data satker (hard delete)
    public function destroy($id)
    {
        try {
            $satker = Satker::findOrFail($id);
            $namaSatker = $satker->nama_satker;

            // Cek apakah satker masih digunakan oleh pengguna atau website
            // Uncomment jika ada relasi
            // if ($satker->pengguna()->count() > 0) {
            //     return redirect()->back()
            //         ->with('error', 'Satuan Kerja tidak dapat dihapus karena masih digunakan oleh ' . $satker->pengguna()->count() . ' pengguna!');
            // }

            $satker->delete();

            // TAMBAHKAN LOG DELETE
            LogAktivitas::log(
                'DELETE',
                'satuan_kerja',
                "Menghapus permanen satuan kerja: {$namaSatker}",
                Auth::id()
            );

            return redirect()
                ->route('superadmin.satuankerja')
                ->with('success', 'Data Satuan Kerja berhasil dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    // HAPUS METHOD-METHOD INI:
    // - softDelete()
    // - arsip()
    // - restore()
    // - forceDelete()
}
