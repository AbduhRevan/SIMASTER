<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\Bidang;
use App\Models\Satker;
use App\Models\Server;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $websites = Website::with(['bidang', 'satker'])->get();
        
        // Hitung statistik
        $total = $websites->count();
        $aktif = $websites->where('status', 'active')->count();
        $maintenance = $websites->where('status', 'maintenance')->count();
        $tidakAktif = $websites->where('status', 'inactive')->count();

        // Data untuk dropdown
        $bidangs = Bidang::all();
        $satkers = Satker::all();
        $servers = Server::all();

        return view('superadmin.website', compact(
            'websites',
            'total',
            'aktif',
            'maintenance',
            'tidakAktif',
            'bidangs',
            'satkers',
            'servers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_website' => 'required|string|max:150',
            'url' => 'required|url|max:255|unique:website,url',
            'bidang_id' => 'nullable|exists:bidang,bidang_id',
            'satker_id' => 'nullable|exists:satuan_kerja,satker_id',
            'status' => 'required|in:active,inactive,maintenance',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'keterangan' => 'nullable|string',
        ]);

        Website::create($validated);

        return redirect()->route('superadmin.website.index')
            ->with('success', 'Website berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $website = Website::findOrFail($id);

        $validated = $request->validate([
            'nama_website' => 'required|string|max:150',
            'url' => 'required|url|max:255|unique:website,url,' . $id . ',website_id',
            'bidang_id' => 'nullable|exists:bidang,bidang_id',
            'satker_id' => 'nullable|exists:satuan_kerja,satker_id',
            'status' => 'required|in:active,inactive,maintenance',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'keterangan' => 'nullable|string',
        ]);

        $website->update($validated);

        return redirect()->route('superadmin.website.index')
            ->with('success', 'Website berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $website = Website::findOrFail($id);
        $website->delete();

        return redirect()->route('superadmin.website.index')
            ->with('success', 'Website berhasil dihapus!');
    }

    public function detail($id)
    {
        $website = Website::with(['bidang', 'satker', 'server'])->findOrFail($id);
        return view('superadmin.website', compact('website'));
    }
}