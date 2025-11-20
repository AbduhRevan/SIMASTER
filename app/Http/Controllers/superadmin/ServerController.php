<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\Server;
use App\Models\superadmin\RakServer;
use App\Models\superadmin\Bidang;
use App\Models\superadmin\Satker;
use App\Models\superadmin\Website;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class ServerController extends Controller
{
    /**
     * Display a listing of servers
     */
    public function index()
    {
        $servers = Server::with(['rak', 'bidang', 'satker', 'website'])->get();
        $raks = RakServer::all();
        $bidangs = Bidang::all();
        $satkers = Satker::all();
        $websites = Website::all();

        // Hitung statistik
        $total = $servers->count();
        $aktif = $servers->where('power_status', 'ON')->count();
        $maintenance = $servers->where('power_status', 'STANDBY')->count();
        $tidakAktif = $servers->where('power_status', 'OFF')->count();

        return view('superadmin.server', compact(
            'servers',
            'total',
            'aktif',
            'maintenance',
            'tidakAktif',
            'raks',
            'bidangs',
            'satkers',
            'websites'
        ));
    }

    /**
     * Store a newly created server
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_server' => 'required|string|max:150|unique:server,nama_server',
            'brand' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',
            'rak_id' => 'nullable|exists:rak_server,rak_id',
            'u_slot' => 'nullable|string|max:50',
            'bidang_id' => 'nullable|exists:bidang,bidang_id',
            'satker_id' => 'nullable|exists:satuan_kerja,satker_id',
            'website_name' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
            'power_status' => 'nullable|in:ON,OFF,STANDBY',
        ]);

        // Set default power status jika tidak ada
        if (!isset($validated['power_status'])) {
            $validated['power_status'] = 'ON';
        }

        // Jika ada website_name, buat website baru atau cari yang sudah ada
        $websiteName = null;
        if (!empty($request->website_name)) {
            $website = Website::firstOrCreate(
                ['nama_website' => $request->website_name],
                [
                    'url' => '#', // default, bisa disesuaikan
                    'status' => 'active',
                    'satker_id' => $request->satker_id,
                    'bidang_id' => $request->bidang_id,
                ]
            );
            $validated['website_id'] = $website->website_id;
            $websiteName = $website->nama_website;
        }

        // Hapus website_name dari validated karena bukan kolom di table server
        unset($validated['website_name']);

        $server = Server::create($validated);

        // TAMBAHKAN LOG CREATE
        $logDetails = [];
        $logDetails[] = "server: {$server->nama_server}";

        if ($server->brand) {
            $logDetails[] = "brand: {$server->brand}";
        }
        if ($server->rak_id) {
            $rakNomor = RakServer::find($server->rak_id)->nomor_rak ?? 'Unknown';
            $logDetails[] = "rak: {$rakNomor}";
            if ($server->u_slot) {
                $logDetails[] = "U-slot: {$server->u_slot}";
            }
        }
        if ($server->bidang_id) {
            $bidangNama = Bidang::find($server->bidang_id)->singkatan_bidang ?? 'Unknown';
            $logDetails[] = "bidang: {$bidangNama}";
        }
        if ($server->satker_id) {
            $satkerNama = Satker::find($server->satker_id)->singkatan_satker ?? 'Unknown';
            $logDetails[] = "satker: {$satkerNama}";
        }
        if ($websiteName) {
            $logDetails[] = "website: {$websiteName}";
        }
        $logDetails[] = "status: {$server->power_status}";

        LogAktivitas::log(
            'CREATE',
            'server',
            "Menambahkan server baru - " . implode(', ', $logDetails),
            Auth::id()
        );

        return redirect()->route('superadmin.server.index')
            ->with('success', 'Server berhasil ditambahkan!');
    }

    /**
     * Display the specified server detail
     */
    public function detail($id)
    {
        $server = Server::with(['rak', 'bidang', 'satker', 'website'])->findOrFail($id);

        // Data untuk dropdown edit
        $raks = RakServer::all();
        $bidangs = Bidang::all();
        $satkers = Satker::all();

        return view('superadmin.server_detail', compact('server', 'raks', 'bidangs', 'satkers'));
    }

    /**
     * Update the specified server
     */
    public function update(Request $request, $id)
    {
        $server = Server::findOrFail($id);

        // Simpan data lama untuk log
        $namaLama = $server->nama_server;
        $brandLama = $server->brand;
        $spesifikasiLama = $server->spesifikasi;
        $rakLama = $server->rak_id;
        $uSlotLama = $server->u_slot;
        $bidangLama = $server->bidang_id;
        $satkerLama = $server->satker_id;
        $powerStatusLama = $server->power_status;

        $validated = $request->validate([
            'nama_server' => 'required|string|max:150|unique:server,nama_server,' . $id . ',server_id',
            'brand' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',
            'rak_id' => 'nullable|exists:rak_server,rak_id',
            'u_slot' => 'nullable|string|max:50',
            'bidang_id' => 'nullable|exists:bidang,bidang_id',
            'satker_id' => 'nullable|exists:satuan_kerja,satker_id',
            'power_status' => 'required|in:ON,OFF,STANDBY',
            'keterangan' => 'nullable|string',
        ]);

        $server->update($validated);

        // TAMBAHKAN LOG UPDATE
        $perubahan = [];

        if ($namaLama !== $request->nama_server) {
            $perubahan[] = "nama dari '{$namaLama}' menjadi '{$request->nama_server}'";
        }
        if ($brandLama !== $request->brand) {
            $brandLamaText = $brandLama ?? 'kosong';
            $brandBaruText = $request->brand ?? 'kosong';
            $perubahan[] = "brand dari '{$brandLamaText}' menjadi '{$brandBaruText}'";
        }
        if ($spesifikasiLama !== $request->spesifikasi) {
            $perubahan[] = "spesifikasi diubah";
        }
        if ($rakLama != $request->rak_id) {
            $rakLamaNomor = $rakLama ? (RakServer::find($rakLama)->nomor_rak ?? 'Unknown') : 'tidak ada';
            $rakBaruNomor = $request->rak_id ? (RakServer::find($request->rak_id)->nomor_rak ?? 'Unknown') : 'tidak ada';
            $perubahan[] = "rak dari {$rakLamaNomor} menjadi {$rakBaruNomor}";
        }
        if ($uSlotLama !== $request->u_slot) {
            $uSlotLamaText = $uSlotLama ?? 'kosong';
            $uSlotBaruText = $request->u_slot ?? 'kosong';
            $perubahan[] = "U-slot dari {$uSlotLamaText} menjadi {$uSlotBaruText}";
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
        if ($powerStatusLama !== $request->power_status) {
            $perubahan[] = "status dari {$powerStatusLama} menjadi {$request->power_status}";
        }

        $deskripsiPerubahan = count($perubahan) > 0
            ? "Mengupdate server {$request->nama_server}: " . implode(', ', $perubahan)
            : "Mengupdate data server: {$request->nama_server}";

        LogAktivitas::log(
            'UPDATE',
            'server',
            $deskripsiPerubahan,
            Auth::id()
        );

        return redirect()->route('superadmin.server.detail', $id)
            ->with('success', 'Server berhasil diperbarui!');
    }

    /**
     * Remove the specified server
     */
    public function destroy($id)
    {
        $server = Server::findOrFail($id);
        $serverName = $server->nama_server;

        // Ambil detail untuk log
        $rakNomor = $server->rak_id ? (RakServer::find($server->rak_id)->nomor_rak ?? 'Unknown') : 'tidak ada';
        $bidangNama = $server->bidang_id ? (Bidang::find($server->bidang_id)->singkatan_bidang ?? 'Unknown') : 'tidak ada';

        $server->delete();

        // TAMBAHKAN LOG DELETE
        LogAktivitas::log(
            'DELETE',
            'server',
            "Menghapus server: {$serverName} (rak: {$rakNomor}, bidang: {$bidangNama})",
            Auth::id()
        );

        return redirect()->route('superadmin.server')
            ->with('success', "Server '{$serverName}' berhasil dihapus!");
    }

    /**
     * Show method (alias untuk detail, untuk backward compatibility)
     */
    public function show($id)
    {
        return $this->detail($id);
    }
}
