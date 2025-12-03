<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class BidangController extends Controller
{
    // Menampilkan semua data bidang
    public function index()
    {
        $bidang = Bidang::paginate(10);
        return view('bidang', compact('bidang'));
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

        LogAktivitas::log(
            'CREATE',
            'bidang',
            "Menambahkan bidang baru: {$bidang->nama_bidang} ({$bidang->singkatan_bidang})",
            Auth::id()
        );

        // Redirect ke halaman yang sesuai
        $page = $request->input('current_page', 1);
        return redirect()->route('superadmin.bidang', ['page' => $page])
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

        // Log perubahan
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

        // Redirect ke halaman yang sesuai
        $page = $request->input('current_page', 1);
        return redirect()->route('superadmin.bidang', ['page' => $page])
            ->with('success', 'Data bidang berhasil diperbarui!');
    }

    // Menghapus data bidang (hard delete)
    public function destroy($id)
    {
        try {
            $bidang = Bidang::findOrFail($id);
            $namaBidang = $bidang->nama_bidang;

            // Ambil current_page dari request
            $page = request()->input('current_page', 1);

            // Cek apakah bidang masih digunakan oleh pengguna
            if ($bidang->pengguna()->count() > 0) {
                return redirect()->route('superadmin.bidang', ['page' => $page])
                    ->with('error', 'Bidang tidak dapat dihapus karena masih digunakan oleh ' . $bidang->pengguna()->count() . ' pengguna!');
            }

            $bidang->delete();

            LogAktivitas::log(
                'DELETE',
                'bidang',
                "Menghapus permanen bidang: {$namaBidang}",
                Auth::id()
            );

            // Redirect ke halaman yang sesuai
            return redirect()->route('superadmin.bidang', ['page' => $page])
                ->with('success', 'Data bidang berhasil dihapus permanen!');
        } catch (\Exception $e) {
            $page = request()->input('current_page', 1);
            return redirect()->route('superadmin.bidang', ['page' => $page])
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
