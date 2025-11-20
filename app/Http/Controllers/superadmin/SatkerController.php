<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\Satker;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class SatkerController extends Controller
{
    // Menampilkan semua data satker (exclude soft deleted)
    public function index()
    {
        $satker = Satker::orderBy('nama_satker')->paginate(6);

        // Log VIEW (opsional, jika ingin mencatat aktivitas melihat halaman)
        LogAktivitas::log(
            'VIEW',
            'satuan_kerja',
            'Melihat halaman daftar satuan kerja',
            Auth::id()
        );

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

    // Soft delete
    public function softDelete($id)
    {
        $satker = Satker::findOrFail($id);
        $namaSatker = $satker->nama_satker; // Simpan nama sebelum delete

        $satker->delete();

        // TAMBAHKAN LOG DELETE
        LogAktivitas::log(
            'DELETE',
            'satuan_kerja',
            "Memindahkan satuan kerja ke arsip: {$namaSatker}",
            Auth::id()
        );

        return redirect()
            ->route('superadmin.satuankerja')
            ->with('success', 'Data Satuan Kerja berhasil dipindahkan ke Arsip Sementara!');
    }

    // Menampilkan data yang di-soft delete (untuk halaman arsip)
    public function arsip()
    {
        $satker = Satker::onlyTrashed()->orderBy('nama_satker')->paginate(10);

        // Log VIEW arsip (opsional)
        LogAktivitas::log(
            'VIEW',
            'satuan_kerja',
            'Melihat halaman arsip satuan kerja',
            Auth::id()
        );

        return view('superadmin.arsip', compact('satker'));
    }

    // Restore data dari soft delete
    public function restore($id)
    {
        $satker = Satker::onlyTrashed()->findOrFail($id);
        $namaSatker = $satker->nama_satker;

        $satker->restore();

        // TAMBAHKAN LOG CREATE (karena dipulihkan)
        LogAktivitas::log(
            'CREATE',
            'satuan_kerja',
            "Memulihkan satuan kerja dari arsip: {$namaSatker}",
            Auth::id()
        );

        return redirect()
            ->route('superadmin.arsip')
            ->with('success', 'Data Satuan Kerja berhasil dipulihkan!');
    }

    // Force delete (hapus permanen)
    public function forceDelete($id)
    {
        $satker = Satker::onlyTrashed()->findOrFail($id);
        $namaSatker = $satker->nama_satker;

        $satker->forceDelete();

        // TAMBAHKAN LOG DELETE
        LogAktivitas::log(
            'DELETE',
            'satuan_kerja',
            "Menghapus permanen satuan kerja dari arsip: {$namaSatker}",
            Auth::id()
        );

        return redirect()
            ->route('superadmin.arsip')
            ->with('success', 'Data Satuan Kerja berhasil dihapus permanen!');
    }
}
