<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\superadmin\Server;
use App\Models\superadmin\RakServer;
use App\Models\superadmin\Bidang;
use App\Models\superadmin\Satker;
use App\Models\superadmin\Website;

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
        }

        // Hapus website_name dari validated karena bukan kolom di table server
        unset($validated['website_name']);

        Server::create($validated);

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
        
        $server->delete();

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