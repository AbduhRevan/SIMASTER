<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RakServer;
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
        $rak = RakServer::with('servers')
            ->orderBy('rak_id', 'asc')
            ->paginate(10);

        return view('rakserver', compact('rak'));
    }

    /**
     * Search untuk live search
     */
    public function search(Request $request)
    {
        try {
            $search = $request->get('search');

            // Query untuk mencari di semua data dengan relasi servers
            $rak = RakServer::with('servers')
                ->where('nomor_rak', 'LIKE', "%{$search}%")
                ->orWhere('ruangan', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%")
                ->orderBy('nomor_rak', 'asc')
                ->get();

            // Hitung U slot terpakai untuk setiap rak
            $rakWithCalculation = $rak->map(function ($item) {
                $terpakai = 0;

                foreach ($item->servers as $server) {
                    if ($server->u_slot) {
                        $slots = explode('-', $server->u_slot);
                        if (count($slots) == 2) {
                            $terpakai += (int)$slots[1] - (int)$slots[0] + 1;
                        } else {
                            $terpakai += 1;
                        }
                    }
                }

                return [
                    'rak_id' => $item->rak_id,
                    'nomor_rak' => $item->nomor_rak,
                    'ruangan' => $item->ruangan,
                    'kapasitas_u_slot' => $item->kapasitas_u_slot,
                    'terpakai' => $terpakai,
                    'keterangan' => strip_tags($item->keterangan), // Strip HTML tags
                ];
            });

            // Hitung total data keseluruhan
            $total = RakServer::count();

            return response()->json([
                'success' => true,
                'data' => $rakWithCalculation,
                'total' => $total,
                'found' => $rakWithCalculation->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
            'keterangan' => 'nullable|string',
        ], [
            'nomor_rak.required' => 'Nomor rak wajib diisi',
            'nomor_rak.unique' => 'Nomor rak sudah terdaftar',
            'nomor_rak.max' => 'Nomor rak maksimal 50 karakter',
            'ruangan.required' => 'Ruangan wajib diisi',
            'ruangan.max' => 'Ruangan maksimal 100 karakter',
            'kapasitas_u_slot.required' => 'Kapasitas U slot wajib diisi',
            'kapasitas_u_slot.min' => 'Kapasitas minimal 1U',
            'kapasitas_u_slot.max' => 'Kapasitas maksimal 50U',
        ]);

        try {
            // Cek apakah kapasitas baru cukup untuk menampung server yang ada
            $terpakaiSekarang = 0;
            foreach ($rak->servers as $server) {
                if ($server->u_slot) {
                    $slots = explode('-', $server->u_slot);
                    if (count($slots) == 2) {
                        $terpakaiSekarang += (int)$slots[1] - (int)$slots[0] + 1;
                    } else {
                        $terpakaiSekarang += 1;
                    }
                }
            }

            if ($request->kapasitas_u_slot < $terpakaiSekarang) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Kapasitas tidak dapat dikurangi menjadi {$request->kapasitas_u_slot}U karena sudah terpakai {$terpakaiSekarang}U!");
            }

            $rak->update([
                'nomor_rak' => $request->nomor_rak,
                'ruangan' => $request->ruangan,
                'kapasitas_u_slot' => $request->kapasitas_u_slot,
                'keterangan' => $request->keterangan,
            ]);

            // Log update
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
            $rak = RakServer::with('servers')->findOrFail($id);
            $nomorRak = $rak->nomor_rak;
            $ruangan = $rak->ruangan;

            // Cek apakah rak masih digunakan oleh server
            $jumlahServer = $rak->servers->count();

            if ($jumlahServer > 0) {
                return redirect()->back()
                    ->with('error', "Rak server '{$nomorRak}' tidak dapat dihapus karena masih digunakan oleh {$jumlahServer} server!");
            }

            $rak->delete();

            LogAktivitas::log(
                'DELETE',
                'rak_server',
                "Menghapus rak server: {$nomorRak} di ruangan {$ruangan}",
                Auth::id()
            );

            return redirect()->route('superadmin.rakserver')
                ->with('success', "Rak server '{$nomorRak}' berhasil dihapus!");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Data Rak Server tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
