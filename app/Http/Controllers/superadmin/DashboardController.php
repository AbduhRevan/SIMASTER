<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\superadmin\Website;
use App\Models\superadmin\Server;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ===== Filter status =====
        $status = $request->get('status');

        $queryWebsite = Website::with('satker');
        $queryServer = Server::with('satker');

        if ($status && $status != 'semua') {
            $queryWebsite->where('status', $status);
            $queryServer->where('status', $status);
        }

        $dataWebsite = $queryWebsite->get();
        $dataServer = $queryServer->get();

        // ===== Hitung ringkasan =====
        $totalWebsite = Website::count();
        $aktifWebsite = Website::where('status', 'active')->count();
        $maintenanceWebsite = Website::where('status', 'maintenance')->count();
        $tidakAktifWebsite = Website::where('status', 'inactive')->count();

        $totalServer = Server::count();
        $aktifServer = Server::where('power_status', 'ON')->count();
        $maintenanceServer = Server::where('power_status', 'STANDBY')->count();
        $tidakAktifServer = Server::where('power_status', 'OFF')->count();

        // Gabungkan website dan server dalam satu array
        $gabunganData = collect();

        foreach ($dataWebsite as $w) {
            $gabunganData->push([
                'tipe' => 'Website',
                'nama' => $w->nama_website,
                'url' => $w->url,
                'status' => $w->status,
                'pic' => $w->satker->nama_satker ?? '-',
                'updated_at' => $w->updated_at,
            ]);
        }

        foreach ($dataServer as $s) {
            $gabunganData->push([
                'tipe' => 'Server',
                'nama' => $s->nama_server,
                'url' => $s->alamat_ip ?? '-',
                'status' => $s->status,
                'pic' => $s->satker->nama_satker ?? '-',
                'updated_at' => $s->updated_at,
            ]);
        }

        return view('superadmin.dashboard', compact(
            'gabunganData',
            'totalWebsite', 'aktifWebsite', 'maintenanceWebsite', 'tidakAktifWebsite',
            'totalServer', 'aktifServer', 'maintenanceServer', 'tidakAktifServer',
            'status'
        ));
    }
}
