<?php

namespace App\Http\Controllers\infratik;

use App\Http\Controllers\Controller;
use App\Models\Pemeliharaan;
use App\Models\infratik\Server;
use App\Models\infratik\Website;
use Illuminate\Http\Request;

class PemeliharaanController extends Controller  // ← GANTI INI (dari InfratikController)
{
    private $bidangId = 3; // Sesuaikan dengan ID bidang Infratik

    public function index(Request $request)
    {
        $query = Pemeliharaan::with(['server', 'website'])
            ->where('bidang_id', $this->bidangId);

        // Filter
        if ($request->search) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }
        if ($request->jenis == 'server') {
            $query->whereNotNull('server_id');
        } elseif ($request->jenis == 'website') {
            $query->whereNotNull('website_id');
        }
        if ($request->status) {
            $query->where('status_pemeliharaan', $request->status);
        }
        if ($request->tanggal) {
            $query->whereDate('tanggal_pemeliharaan', $request->tanggal);
        }

        $pemeliharaan = $query->orderBy('tanggal_pemeliharaan', 'desc')->paginate(10);

        // Statistik
        $totalPemeliharaan = Pemeliharaan::where('bidang_id', $this->bidangId)->count();
        $totalServer = Pemeliharaan::where('bidang_id', $this->bidangId)->whereNotNull('server_id')->count();
        $totalWebsite = Pemeliharaan::where('bidang_id', $this->bidangId)->whereNotNull('website_id')->count();
        $berlangsung = Pemeliharaan::where('bidang_id', $this->bidangId)->where('status_pemeliharaan', 'berlangsung')->count();

        // Asset sesuai bidang
        $servers = Server::where('bidang_id', $this->bidangId)
            ->whereNotIn('power_status', ['maintenance'])->get();
        $websites = Website::where('bidang_id', $this->bidangId)
            ->where('status', '!=', 'maintenance')->get();

        return view('infratik.pemeliharaan', compact(
            'pemeliharaan',
            'totalPemeliharaan',
            'totalServer',
            'totalWebsite',
            'berlangsung',
            'servers',
            'websites'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_asset' => 'required|in:server,website',
            'keterangan' => 'required|string',
        ]);

        $data = [
            'tanggal_pemeliharaan' => $request->tanggal_pemeliharaan,
            'keterangan' => $request->keterangan,
            'status_pemeliharaan' => 'dijadwalkan',
            'bidang_id' => $this->bidangId,
        ];

        if ($request->jenis_asset === 'server') {
            $request->validate(['server_id' => 'required|exists:server,server_id']);
            $data['server_id'] = $request->server_id;
        } else {
            $request->validate(['website_id' => 'required|exists:website,website_id']);
            $data['website_id'] = $request->website_id;
        }

        Pemeliharaan::create($data);

        return redirect()->back()->with('success', 'Jadwal pemeliharaan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $pemeliharaan = Pemeliharaan::where('pemeliharaan_id', $id)
            ->where('bidang_id', $this->bidangId)
            ->firstOrFail();

        $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_asset' => 'required|in:server,website',
            'keterangan' => 'required|string',
        ]);

        $data = [
            'tanggal_pemeliharaan' => $request->tanggal_pemeliharaan,
            'keterangan' => $request->keterangan,
        ];

        if ($request->jenis_asset === 'server') {
            $data['server_id'] = $request->server_id;
            $data['website_id'] = null;
        } else {
            $data['website_id'] = $request->website_id;
            $data['server_id'] = null;
        }

        $pemeliharaan->update($data);

        return redirect()->back()->with('success', 'Data pemeliharaan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $pemeliharaan = Pemeliharaan::where('pemeliharaan_id', $id)
            ->where('bidang_id', $this->bidangId)
            ->firstOrFail();

        $pemeliharaan->delete();

        return redirect()->back()->with('success', 'Data pemeliharaan berhasil dihapus!');
    }

    public function start($id)
    {
        $pemeliharaan = Pemeliharaan::where('pemeliharaan_id', $id)
            ->where('bidang_id', $this->bidangId)
            ->firstOrFail();

        if ($pemeliharaan->canStart()) {
            $pemeliharaan->update(['status_pemeliharaan' => 'berlangsung']);
            return redirect()->back()->with('success', 'Pemeliharaan dimulai!');
        }

        return redirect()->back()->with('error', 'Tidak dapat memulai pemeliharaan!');
    }

    public function finish($id)
    {
        $pemeliharaan = Pemeliharaan::where('pemeliharaan_id', $id)
            ->where('bidang_id', $this->bidangId)
            ->firstOrFail();

        if ($pemeliharaan->canFinish()) {
            $pemeliharaan->update([
                'status_pemeliharaan' => 'selesai',
                'tanggal_selesai_aktual' => now()
            ]);
            return redirect()->back()->with('success', 'Pemeliharaan selesai!');
        }

        return redirect()->back()->with('error', 'Tidak dapat menyelesaikan pemeliharaan!');
    }

    public function cancel($id)
    {
        $pemeliharaan = Pemeliharaan::where('pemeliharaan_id', $id)
            ->where('bidang_id', $this->bidangId)
            ->firstOrFail();

        if ($pemeliharaan->canCancel()) {
            $pemeliharaan->update(['status_pemeliharaan' => 'dibatalkan']);
            return redirect()->back()->with('success', 'Pemeliharaan dibatalkan!');
        }

        return redirect()->back()->with('error', 'Tidak dapat membatalkan pemeliharaan!');
    }
}