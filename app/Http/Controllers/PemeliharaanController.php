<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\superadmin\Server;
use App\Models\superadmin\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeliharaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemeliharaan::with(['server', 'website']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('server', function ($q) use ($search) {
                        $q->where('nama_server', 'like', "%{$search}%");
                    })
                    ->orWhereHas('website', function ($q) use ($search) {
                        $q->where('nama_website', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            if ($request->jenis == 'server') {
                $query->whereNotNull('server_id');
            } elseif ($request->jenis == 'website') {
                $query->whereNotNull('website_id');
            }
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pemeliharaan', $request->tanggal);
        }

        // Ambil data dengan pagination
        $pemeliharaan = $query->orderBy('tanggal_pemeliharaan', 'desc')->paginate(10);

        // Hitung statistik
        $totalPemeliharaan = Pemeliharaan::count();
        $totalServer = Pemeliharaan::whereNotNull('server_id')->count();
        $totalWebsite = Pemeliharaan::whereNotNull('website_id')->count();

        // Ambil data server dan website untuk dropdown
        // Pilih server yang power_status ON atau STANDBY
        $servers = Server::whereIn('power_status', ['ON', 'STANDBY'])->orderBy('nama_server')->get();
        $websites = Website::where('status', 'active')->orderBy('nama_website')->get();

        return view('superadmin.pemeliharaan', compact(
            'pemeliharaan',
            'totalPemeliharaan',
            'totalServer',
            'totalWebsite',
            'servers',
            'websites'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_asset' => 'required|in:server,website',
            'server_id' => 'required_if:jenis_asset,server|nullable|exists:server,server_id',
            'website_id' => 'required_if:jenis_asset,website|nullable|exists:website,website_id',
            'keterangan' => 'required|string',
        ], [
            'tanggal_pemeliharaan.required' => 'Tanggal pemeliharaan harus diisi',
            'jenis_asset.required' => 'Jenis asset harus dipilih',
            'server_id.required_if' => 'Server harus dipilih',
            'website_id.required_if' => 'Website harus dipilih',
            'keterangan.required' => 'Keterangan harus diisi',
        ]);

        try {
            DB::beginTransaction();

            Pemeliharaan::create([
                'server_id' => $request->jenis_asset == 'server' ? $request->server_id : null,
                'website_id' => $request->jenis_asset == 'website' ? $request->website_id : null,
                'tanggal_pemeliharaan' => $request->tanggal_pemeliharaan,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return redirect()->route('superadmin.pemeliharaan')
                ->with('success', 'Data pemeliharaan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data pemeliharaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_asset' => 'required|in:server,website',
            'server_id' => 'required_if:jenis_asset,server|nullable|exists:server,server_id',
            'website_id' => 'required_if:jenis_asset,website|nullable|exists:website,website_id',
            'keterangan' => 'required|string',
        ], [
            'tanggal_pemeliharaan.required' => 'Tanggal pemeliharaan harus diisi',
            'jenis_asset.required' => 'Jenis asset harus dipilih',
            'server_id.required_if' => 'Server harus dipilih',
            'website_id.required_if' => 'Website harus dipilih',
            'keterangan.required' => 'Keterangan harus diisi',
        ]);

        try {
            DB::beginTransaction();

            $pemeliharaan = Pemeliharaan::findOrFail($id);

            $pemeliharaan->update([
                'server_id' => $request->jenis_asset == 'server' ? $request->server_id : null,
                'website_id' => $request->jenis_asset == 'website' ? $request->website_id : null,
                'tanggal_pemeliharaan' => $request->tanggal_pemeliharaan,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return redirect()->route('superadmin.pemeliharaan')
                ->with('success', 'Data pemeliharaan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data pemeliharaan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pemeliharaan = Pemeliharaan::findOrFail($id);
            $pemeliharaan->delete();

            DB::commit();

            return redirect()->route('superadmin.pemeliharaan')
                ->with('success', 'Data pemeliharaan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus data pemeliharaan: ' . $e->getMessage());
        }
    }
}
