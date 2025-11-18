<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\RakServer;
use Illuminate\Support\Facades\DB;

class RakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua rak beserta relasi server untuk hitung terpakai
        $rak = RakServer::with('servers')->orderBy('rak_id', 'desc')->get();
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
            RakServer::create([
                'nomor_rak' => $request->nomor_rak,
                'ruangan' => $request->ruangan,
                'kapasitas_u_slot' => $request->kapasitas_u_slot,
                'keterangan' => $request->keterangan,
            ]);

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
            
            // Cek apakah rak masih digunakan oleh server
            if ($rak->servers()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Rak server tidak dapat dihapus karena masih digunakan oleh ' . $rak->servers()->count() . ' server!');
            }
            
            $rak->delete();

            return redirect()->route('superadmin.rakserver')
                ->with('success', 'Data Rak Server berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}