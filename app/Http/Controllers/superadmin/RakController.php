<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\RakServer;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // UBAH BARIS INI - Ganti dari ::get() ke ::paginate(10)
        $rak = RakServer::with('servers')
            ->orderBy('rak_id', 'asc')
            ->paginate(10); // Tampilkan 10 data per halaman

        return view('superadmin.rakserver', compact('rak'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_rak' => 'required|string|max:50|unique:rak_server,nomor_rak',
            'ruangan' => 'required|string|max:100',
            'kapasitas_u_slot' => 'required|integer|min:1|max:50',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nomor_rak.required' => 'Nomor rak wajib diisi',
            'nomor_rak.unique' => 'Nomor rak sudah terdaftar',
            'nomor_rak.max' => 'Nomor rak maksimal 50 karakter',
            'ruangan.required' => 'Ruangan wajib diisi',
            'ruangan.max' => 'Ruangan maksimal 100 karakter',
            'kapasitas_u_slot.required' => 'Kapasitas U slot wajib diisi',
            'kapasitas_u_slot.min' => 'Kapasitas minimal 1U',
            'kapasitas_u_slot.max' => 'Kapasitas maksimal 50U',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
        ]);

        try {
            $rak = RakServer::create([
                'nomor_rak' => $request->nomor_rak,
                'ruangan' => $request->ruangan,
                'kapasitas_u_slot' => $request->kapasitas_u_slot,
                'keterangan' => $request->keterangan,
            ]);

            // TAMBAHKAN LOG CREATE
            LogAktivitas::log(
                'CREATE',
                'rak_server',
                "Menambahkan rak server baru: {$rak->nomor_rak} di ruangan {$rak->ruangan} dengan kapasitas {$rak->kapasitas_u_slot}U",
                Auth::id()
            );

            return redirect()->route('superadmin.rakserver')
                ->with('success', 'Data Rak Server berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rak = RakServer::findOrFail($id);

        // Simpan data lama untuk log
        $nomorLama = $rak->nomor_rak;
        $ruanganLama = $rak->ruangan;
        $kapasitasLama = $rak->kapasitas_u_slot;

        $request->validate([
            'nomor_rak' => 'required|string|max:50|unique:rak_server,nomor_rak,' . $id . ',rak_id',
            'ruangan' => 'required|string|max:100',
            'kapasitas_u_slot' => 'required|integer|min:1|max:50',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nomor_rak.required' => 'Nomor rak wajib diisi',
            'nomor_rak.unique' => 'Nomor rak sudah terdaftar',
            'nomor_rak.max' => 'Nomor rak maksimal 50 karakter',
            'ruangan.required' => 'Ruangan wajib diisi',
            'ruangan.max' => 'Ruangan maksimal 100 karakter',
            'kapasitas_u_slot.required' => 'Kapasitas U slot wajib diisi',
            'kapasitas_u_slot.min' => 'Kapasitas minimal 1U',
            'kapasitas_u_slot.max' => 'Kapasitas maksimal 50U',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
        ]);

        try {
            $rak->update([
                'nomor_rak' => $request->nomor_rak,
                'ruangan' => $request->ruangan,
                'kapasitas_u_slot' => $request->kapasitas_u_slot,
                'keterangan' => $request->keterangan,
            ]);

            // TAMBAHKAN LOG UPDATE
            $perubahan = [];
            if ($nomorLama !== $request->nomor_rak) {
                $perubahan[] = "nomor rak dari '{$nomorLama}' menjadi '{$request->nomor_rak}'";
            }
            if ($ruanganLama !== $request->ruangan) {
                $perubahan[] = "ruangan dari '{$ruanganLama}' menjadi '{$request->ruangan}'";
            }
            if ($kapasitasLama != $request->kapasitas_u_slot) {
                $perubahan[] = "kapasitas dari {$kapasitasLama}U menjadi {$request->kapasitas_u_slot}U";
            }

            $deskripsiPerubahan = count($perubahan) > 0
                ? "Mengupdate rak server {$request->nomor_rak}: " . implode(', ', $perubahan)
                : "Mengupdate rak server: {$request->nomor_rak}";

            LogAktivitas::log(
                'UPDATE',
                'rak_server',
                $deskripsiPerubahan,
                Auth::id()
            );

            return redirect()->route('superadmin.rakserver')
                ->with('success', 'Data Rak Server berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $rak = RakServer::findOrFail($id);
            $nomorRak = $rak->nomor_rak;
            $ruangan = $rak->ruangan;

            // Cek apakah rak masih digunakan oleh server
            if ($rak->servers()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Rak server tidak dapat dihapus karena masih digunakan oleh ' . $rak->servers()->count() . ' server!');
            }

            $rak->delete();

            // TAMBAHKAN LOG DELETE
            LogAktivitas::log(
                'DELETE',
                'rak_server',
                "Menghapus rak server: {$nomorRak} di ruangan {$ruangan}",
                Auth::id()
            );

            return redirect()->route('superadmin.rakserver')
                ->with('success', 'Data Rak Server berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
