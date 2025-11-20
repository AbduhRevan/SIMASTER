<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\Bidang;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class BidangController extends Controller
{
    // Menampilkan semua data bidang
    public function index()
    {
        $bidang = Bidang::all();
        return view('superadmin.bidang', compact('bidang'));
    }

    // Menyimpan data bidang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'singkatan_bidang' => 'required|string|max:100',
        ]);

        $bidang = Bidang::create([
            'nama_bidang' => $request->nama_bidang,
            'singkatan_bidang' => $request->singkatan_bidang,
        ]);

        // TAMBAHKAN LOG CREATE
        LogAktivitas::log(
            'CREATE',
            'bidang',
            "Menambahkan bidang baru: {$bidang->nama_bidang} ({$bidang->singkatan_bidang})",
            Auth::id()
        );

        return redirect()->route('superadmin.bidang')
            ->with('success', 'Bidang baru berhasil ditambahkan!');
    }

    // Mengupdate data bidang
    public function update(Request $request, $id)
    {
        $bidang = Bidang::findOrFail($id);

        // Simpan data lama untuk log
        $namaLama = $bidang->nama_bidang;
        $singkatanLama = $bidang->singkatan_bidang;

        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'singkatan_bidang' => 'required|string|max:100',
        ]);

        $bidang->update([
            'nama_bidang' => $request->nama_bidang,
            'singkatan_bidang' => $request->singkatan_bidang,
        ]);

        // TAMBAHKAN LOG UPDATE
        $perubahan = [];
        if ($namaLama !== $request->nama_bidang) {
            $perubahan[] = "nama dari '{$namaLama}' menjadi '{$request->nama_bidang}'";
        }
        if ($singkatanLama !== $request->singkatan_bidang) {
            $perubahan[] = "singkatan dari '{$singkatanLama}' menjadi '{$request->singkatan_bidang}'";
        }

        $deskripsiPerubahan = count($perubahan) > 0
            ? "Mengupdate bidang: " . implode(', ', $perubahan)
            : "Mengupdate bidang: {$request->nama_bidang}";

        LogAktivitas::log(
            'UPDATE',
            'bidang',
            $deskripsiPerubahan,
            Auth::id()
        );

        return redirect()->route('superadmin.bidang')
            ->with('success', 'Data bidang berhasil diperbarui!');
    }

    // Menghapus data bidang (soft delete)
    public function softDelete($id)
    {
        $bidang = Bidang::findOrFail($id);
        $namaBidang = $bidang->nama_bidang;

        $bidang->delete();

        // TAMBAHKAN LOG DELETE
        LogAktivitas::log(
            'DELETE',
            'bidang',
            "Memindahkan bidang ke arsip: {$namaBidang}",
            Auth::id()
        );

        return redirect()->route('superadmin.bidang')
            ->with('success', 'Data bidang berhasil dipindahkan ke arsip sementara.');
    }

    // Halaman Arsip
    public function arsip()
    {
        $bidangTerhapus = Bidang::onlyTrashed()->get();
        return view('superadmin.arsip', compact('bidangTerhapus'));
    }

    // Mengembalikan data bidang yang sudah dihapus
    public function restore($id)
    {
        $bidang = Bidang::withTrashed()->findOrFail($id);
        $namaBidang = $bidang->nama_bidang;

        $bidang->restore();

        // TAMBAHKAN LOG CREATE (karena dipulihkan)
        LogAktivitas::log(
            'CREATE',
            'bidang',
            "Memulihkan bidang dari arsip: {$namaBidang}",
            Auth::id()
        );

        return redirect()->route('superadmin.arsip')
            ->with('success', 'Data bidang berhasil dipulihkan.');
    }

    // Hapus Permanen
    public function forceDelete($id)
    {
        $bidang = Bidang::withTrashed()->findOrFail($id);
        $namaBidang = $bidang->nama_bidang;

        $bidang->forceDelete();

        // TAMBAHKAN LOG DELETE
        LogAktivitas::log(
            'DELETE',
            'bidang',
            "Menghapus permanen bidang dari arsip: {$namaBidang}",
            Auth::id()
        );

        return redirect()->route('superadmin.arsip')
            ->with('success', 'Data bidang berhasil dihapus permanen.');
    }
}
