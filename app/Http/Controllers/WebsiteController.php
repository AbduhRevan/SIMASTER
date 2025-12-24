<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\Bidang;
use App\Models\Satker;
use App\Models\Server;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class WebsiteController extends Controller
{
    public function index(Request $request)
    {
        // website berdasarkan bidang/role
        $query = Website::with(['bidang', 'satker', 'server']);
        if (!in_array(Auth()->user()->role, ['superadmin', 'pimpinan'])) {
            $id_bidang = Auth()->user()->bidang_id ?? Auth()->user()->bidang;
            $query->where('bidang_id', $id_bidang);
        }
        
        // pencarian teks (nama atau url)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('nama_website', 'like', "%{$q}%")
                    ->orWhere('url', 'like', "%{$q}%");
            });
        }

        // filter server (server_id)
        if($request->filled('server')) {
            $serverId = $request->input('server');
            $query->where('server_id', $serverId);
        }

        // filter bidang (kirim nama_bidang dari blade)
        if ($request->filled('bidang')) {
            $query->whereHas('bidang', function($q) use ($request) {
                $q->where('nama_bidang', $request->bidang);
            });
        }

        // filter satker (kirim nama_satker dari blade)
        if ($request->filled('satker')) {
            $query->whereHas('satker', function($q) use ($request) {
                $q->where('nama_satker', $request->satker);
            });
        }

        // filter status (kirim values: active, maintenance, inactive)
        if ($request->filled('status')) {
            $statusMap = [
                'ON' => 'active',
                'STANDBY' => 'maintenance',
                'OFF' => 'inactive',
                'active' => 'active',
                'maintenance' => 'maintenance',
                'inactive' => 'inactive'
            ];
            $statusReq = $request->status;
            if(isset($statusMap[$statusReq])) {
                $query->where('status', $statusMap[$statusReq]);
            }
        }

        // Hitung statistik dari query sebelum pagination
        $total = (clone $query)->count();
        $aktif = (clone $query)->where('status', 'active')->count();
        $maintenance = (clone $query)->where('status', 'maintenance')->count();
        $tidakAktif = (clone $query)->where('status', 'inactive')->count();

        // Pagination
        $websites = $query->latest()->paginate(9)->appends($request->except('page'));

        $bidangs = Bidang::all();
        $satkers = Satker::all();
        $servers = Server::all();

        // Cek apakah user adalah admin bidang
        $bidangOperatorRoles = ['operator banglola', 'operator pamsis', 'operator infratik', 'operator tatausaha'];
        $isBidangAdmin = in_array(auth()->user()->role, $bidangOperatorRoles);

        return view('website', compact(
            'websites',
            'total',
            'aktif',
            'maintenance',
            'tidakAktif',
            'bidangs',
            'satkers',
            'servers',
            'isBidangAdmin'
        ));
    }

    public function exportPDF(Request $request)
    {
        // Ambil parameter filter
        $server = $request->input('server');
        $bidang = $request->input('bidang');
        $satker = $request->input('satker');
        $status = $request->input('status');

        // Query dengan filter
        $query = Website::with(['server.rak', 'bidang', 'satker']);
        if (!in_array(Auth()->user()->role, ['superadmin', 'pimpinan'])) {
            $id_bidang = Auth()->user()->bidang_id ?? Auth()->user()->bidang;
            $query->where('bidang_id', $id_bidang);
        }
        
        if ($server) {
            $query->where('server_id', $server);
        }

        if ($bidang) {
            $query->whereHas('bidang', function($q) use ($bidang) {
                $q->where('nama_bidang', $bidang);
            });
        }

        if ($satker) {
            $query->whereHas('satker', function($q) use ($satker) {
                $q->where('nama_satker', $satker);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $websites = $query->orderBy('nama_website')->get();

        // Hitung statistik
        $total = $websites->count();
        $aktif = $websites->where('status', 'active')->count();
        $tidakAktif = $websites->where('status', 'inactive')->count();
        $maintenance = $websites->where('status', 'maintenance')->count();

        // Load view PDF
        $pdf = Pdf::loadView('website.pdf', compact(
            'websites',
            'total',
            'aktif',
            'tidakAktif',
            'maintenance',
            'server',
            'bidang',
            'satker',
            'status'
        ));

        $pdf->setPaper('A4', 'landscape');

        $filename = 'Laporan_Website_' . date('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    public function store(Request $request)
    {
        $bidangOperatorRoles = ['operator banglola', 'operator pamsis', 'operator infratik', 'operator tatausaha'];
    
        // Kalau admin bidang submit, pastikan bidang_id sesuai dengan bidangnya
        if (in_array(auth()->user()->role, $bidangOperatorRoles)) {
            if ($request->bidang_id && $request->bidang_id != auth()->user()->bidang_id) {
                return back()->withErrors(['bidang_id' => 'Anda hanya bisa memilih bidang Anda sendiri']);
            }
        }

        $validated = $request->validate([
            'nama_website' => 'required|string|max:150',
            'url' => 'required|string|max:255|unique:website,url',
            'bidang_id' => 'nullable|exists:bidang,bidang_id',
            'satker_id' => 'nullable|exists:satuan_kerja,satker_id',
            'server_id' => 'nullable|exists:server,server_id',
            'status' => 'required|in:active,inactive,maintenance',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'keterangan' => 'nullable|string',
        ]);

        $website = Website::create($validated);

        // Log
        $logDetails = [];
        $logDetails[] = "website: {$website->nama_website}";
        $logDetails[] = "URL: {$website->url}";

        if ($website->bidang_id) {
            $bidangNama = Bidang::find($website->bidang_id)->singkatan_bidang ?? 'Unknown';
            $logDetails[] = "bidang: {$bidangNama}";
        }
        if ($website->satker_id) {
            $satkerNama = Satker::find($website->satker_id)->singkatan_satker ?? 'Unknown';
            $logDetails[] = "satker: {$satkerNama}";
        }
        if ($website->server_id) {
            $serverNama = Server::find($website->server_id)->nama_server ?? 'Unknown';
            $logDetails[] = "server: {$serverNama}";
        }
        if ($website->tahun_pengadaan) {
            $logDetails[] = "tahun: {$website->tahun_pengadaan}";
        }
        $logDetails[] = "status: " . $this->getStatusLabel($website->status);

        LogAktivitas::log(
            'CREATE',
            'website',
            "Menambahkan website baru - " . implode(', ', $logDetails),
            Auth::id()
        );

        $page = $request->input('current_page', 1);
        return redirect()->route('website.index', ['page' => $page])
            ->with('success', 'Website berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $bidangOperatorRoles = ['operator banglola', 'operator pamsis', 'operator infratik', 'operator tatausaha'];
    
        // Kalau admin bidang submit, pastikan bidang_id sesuai dengan bidangnya
        if (in_array(auth()->user()->role, $bidangOperatorRoles)) {
            if ($request->bidang_id && $request->bidang_id != auth()->user()->bidang_id) {
                return back()->withErrors(['bidang_id' => 'Anda hanya bisa memilih bidang Anda sendiri']);
            }
        }

        // Cek jika pimpinan, tolak akses
        if (auth()->user()->role == 'pimpinan') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data');
        }

        $website = Website::findOrFail($id);

        // Simpan data lama untuk log
        $namaLama = $website->nama_website;
        $urlLama = $website->url;
        $bidangLama = $website->bidang_id;
        $satkerLama = $website->satker_id;
        $serverLama = $website->server_id;
        $statusLama = $website->status;
        $tahunLama = $website->tahun_pengadaan;

        $validated = $request->validate([
            'nama_website' => 'required|string|max:150',
            'url' => 'required|url|max:255|unique:website,url,' . $id . ',website_id',
            'bidang_id' => 'nullable|exists:bidang,bidang_id',
            'satker_id' => 'nullable|exists:satuan_kerja,satker_id',
            'server_id' => 'nullable|exists:server,server_id',
            'status' => 'required|in:active,inactive,maintenance',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'keterangan' => 'nullable|string',
        ]);

        $website->update($validated);

        // Log update
        $perubahan = [];

        if ($namaLama !== $request->nama_website) {
            $perubahan[] = "nama dari '{$namaLama}' menjadi '{$request->nama_website}'";
        }
        if ($urlLama !== $request->url) {
            $perubahan[] = "URL dari '{$urlLama}' menjadi '{$request->url}'";
        }
        if ($bidangLama != $request->bidang_id) {
            $bidangLamaNama = $bidangLama ? (Bidang::find($bidangLama)->singkatan_bidang ?? 'Unknown') : 'tidak ada';
            $bidangBaruNama = $request->bidang_id ? (Bidang::find($request->bidang_id)->singkatan_bidang ?? 'Unknown') : 'tidak ada';
            $perubahan[] = "bidang dari {$bidangLamaNama} menjadi {$bidangBaruNama}";
        }
        if ($satkerLama != $request->satker_id) {
            $satkerLamaNama = $satkerLama ? (Satker::find($satkerLama)->singkatan_satker ?? 'Unknown') : 'tidak ada';
            $satkerBaruNama = $request->satker_id ? (Satker::find($request->satker_id)->singkatan_satker ?? 'Unknown') : 'tidak ada';
            $perubahan[] = "satker dari {$satkerLamaNama} menjadi {$satkerBaruNama}";
        }
        if ($serverLama != $request->server_id) {
            $serverLamaNama = $serverLama ? (Server::find($serverLama)->nama_server ?? 'Unknown') : 'tidak ada';
            $serverBaruNama = $request->server_id ? (Server::find($request->server_id)->nama_server ?? 'Unknown') : 'tidak ada';
            $perubahan[] = "server dari {$serverLamaNama} menjadi {$serverBaruNama}";
        }
        if ($statusLama !== $request->status) {
            $statusLamaLabel = $this->getStatusLabel($statusLama);
            $statusBaruLabel = $this->getStatusLabel($request->status);
            $perubahan[] = "status dari {$statusLamaLabel} menjadi {$statusBaruLabel}";
        }
        if ($tahunLama != $request->tahun_pengadaan) {
            $tahunLamaText = $tahunLama ?? 'tidak ada';
            $tahunBaruText = $request->tahun_pengadaan ?? 'tidak ada';
            $perubahan[] = "tahun pengadaan dari {$tahunLamaText} menjadi {$tahunBaruText}";
        }

        $deskripsiPerubahan = count($perubahan) > 0
            ? "Mengupdate website {$request->nama_website}: " . implode(', ', $perubahan)
            : "Mengupdate data website: {$request->nama_website}";

        LogAktivitas::log(
            'UPDATE',
            'website',
            $deskripsiPerubahan,
            Auth::id()
        );

        $page = $request->input('current_page', 1);
        return redirect()->route('website.index', ['page' => $page])
            ->with('success', 'Website berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        // Cek jika pimpinan, tolak akses
        if (auth()->user()->role == 'pimpinan') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data');
        }

        $website = Website::findOrFail($id);

        // Simpan data untuk log
        $namaWebsite = $website->nama_website;
        $urlWebsite = $website->url;
        $bidangNama = $website->bidang_id ? (Bidang::find($website->bidang_id)->singkatan_bidang ?? 'Unknown') : 'tidak ada';

        $website->delete();

        // Log delete
        LogAktivitas::log(
            'DELETE',
            'website',
            "Menghapus website: {$namaWebsite} ({$urlWebsite}) dari bidang {$bidangNama}",
            Auth::id()
        );

        $page = $request->input('current_page', 1);
        return redirect()->route('website.index', ['page' => $page])
            ->with('success', 'Website berhasil dihapus!');
    }

    public function detail(Request $request, $id)
    {
        $website = Website::with(['bidang', 'satker', 'server'])->findOrFail($id);

        // Jika request dari AJAX, return JSON
        if ($request->ajax()) {
            return response()->json($website);
        }

        // Jika diakses langsung via URL, redirect ke index
        return redirect()->route('website.index');
    }

    public function edit($id)
    {
        if (auth()->user()->role == 'pimpinan') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit data');
        }
    
        $website = Website::with(['bidang', 'satker', 'server'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $website
        ]);
    }

    /**
     * Helper method untuk format status label
     */
    private function getStatusLabel($status)
    {
        return match ($status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'maintenance' => 'Maintenance',
            default => ucfirst($status)
        };
    }
}