<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\Server;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $status = $request->get('status', 'semua');
    $search = $request->get('search');

    // Ambil bidang user jika bukan superadmin
    $id_bidang = null;
    if (auth()->user()->role != 'superadmin') {
        $id_bidang = auth()->user()->bidang_id ?? auth()->user()->bidang;
    }

    /* ==========================
     *       QUERY WEBSITE
     * ========================== */
    $queryWebsite = Website::with('satker');

    // Filter bidang untuk website
    if ($id_bidang) {
        $queryWebsite->whereHas('satker', function ($q) use ($id_bidang) {
            $q->where('bidang_id', $id_bidang);
        });
    }

    // Filter status
    if ($status !== 'semua') {
        $queryWebsite->where('status', $status);
    }

    // Search
    if ($search) {
        $queryWebsite->where(function ($q) use ($search) {
            $q->where('nama_website', 'LIKE', "%{$search}%")
                ->orWhere('url', 'LIKE', "%{$search}%")
                ->orWhereHas('satker', function ($sq) use ($search) {
                    $sq->where('nama_satker', 'LIKE', "%{$search}%");
                });
        });
    }

    $dataWebsite = $queryWebsite->get();


    /* ==========================
     *         QUERY SERVER
     * ========================== */
    $queryServer = Server::with('satker');

    // Filter bidang untuk server
    if ($id_bidang) {
        $queryServer->whereHas('satker', function ($q) use ($id_bidang) {
            $q->where('bidang_id', $id_bidang);
        });
    }

    // Filter status (mapping)
    if ($status !== 'semua') {
        $mapping = [
            'active' => 'ON',
            'maintenance' => 'STANDBY',
            'inactive' => 'OFF'
        ];
        $queryServer->where('power_status', $mapping[$status] ?? null);
    }

    // Search
    if ($search) {
        $queryServer->where(function ($q) use ($search) {
            $q->where('nama_server', 'LIKE', "%{$search}%")
                ->orWhere('spesifikasi', 'LIKE', "%{$search}%")
                ->orWhereHas('satker', function ($sq) use ($search) {
                    $sq->where('nama_satker', 'LIKE', "%{$search}%");
                });
        });
    }

    $dataServer = $queryServer->get();


    /* ==========================
     *        HITUNG TOTAL
     * ========================== */
    if ($id_bidang) {
        // Hitung hanya milik bidang user
        $totalWebsite = Website::whereHas('satker', fn($q) => $q->where('bidang_id', $id_bidang))->count();
        $aktifWebsite = Website::where('status', 'active')
            ->whereHas('satker', fn($q) => $q->where('bidang_id', $id_bidang))->count();
        $maintenanceWebsite = Website::where('status', 'maintenance')
            ->whereHas('satker', fn($q) => $q->where('bidang_id', $id_bidang))->count();
        $tidakAktifWebsite = Website::where('status', 'inactive')
            ->whereHas('satker', fn($q) => $q->where('bidang_id', $id_bidang))->count();

        $totalServer = Server::whereHas('satker', fn($q) => $q->where('bidang_id', $id_bidang))->count();
        $aktifServer = Server::where('power_status', 'ON')
            ->whereHas('satker', fn($q) => $q->where('bidang_id', $id_bidang))->count();
        $maintenanceServer = Server::where('power_status', 'STANDBY')
            ->whereHas('satker', fn($q) => $q->where('bidang_id', $id_bidang))->count();
        $tidakAktifServer = Server::where('power_status', 'OFF')
            ->whereHas('satker', fn($q) => $q->where('bidang_id', $id_bidang))->count();

    } else {
        // Superadmin → semua
        $totalWebsite = Website::count();
        $aktifWebsite = Website::where('status', 'active')->count();
        $maintenanceWebsite = Website::where('status', 'maintenance')->count();
        $tidakAktifWebsite = Website::where('status', 'inactive')->count();

        $totalServer = Server::count();
        $aktifServer = Server::where('power_status', 'ON')->count();
        $maintenanceServer = Server::where('power_status', 'STANDBY')->count();
        $tidakAktifServer = Server::where('power_status', 'OFF')->count();
    }


    /* ==========================
     *      GABUNG DATA
     * ========================== */
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
        $statusMapping = [
            'ON' => 'active',
            'STANDBY' => 'maintenance',
            'OFF' => 'inactive'
        ];

        $gabunganData->push([
            'tipe' => 'Server',
            'nama' => $s->nama_server,
            'url' => $s->alamat_ip ?? '-',
            'status' => $statusMapping[$s->power_status] ?? 'inactive',
            'pic' => $s->satker->nama_satker ?? '-',
            'updated_at' => $s->updated_at,
        ]);
    }

    $gabunganData = $gabunganData->sortByDesc('updated_at')->values();

    return view('dashboard', compact(
        'gabunganData',
        'totalWebsite',
        'aktifWebsite',
        'maintenanceWebsite',
        'tidakAktifWebsite',
        'totalServer',
        'aktifServer',
        'maintenanceServer',
        'tidakAktifServer',
        'status'
    ));

}
}