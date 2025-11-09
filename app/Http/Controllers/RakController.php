<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RakServer; // pakai model
use Illuminate\Support\Facades\DB;

class RakController extends Controller
{
    public function index()
    {
        $rak = RakServer::all(); // ambil semua data dari tabel
        return view('superadmin.rakserver', compact('rak'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_rak' => 'required|string|max:20',
            'ruangan' => 'required|string|max:100',
            'kapasitas_u_slot' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        RakServer::create([
            'nomor_rak' => $request->nomor_rak,
            'ruangan' => $request->ruangan,
            'kapasitas_u_slot' => $request->kapasitas_u_slot,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('superadmin.rakserver')->with('success', 'Data Rak Server berhasil ditambahkan!');
    }
}
