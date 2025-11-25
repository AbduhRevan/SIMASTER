<?php

namespace App\Http\Controllers\banglola;

use App\Http\Controllers\Controller;
use App\Models\banglola\Website;
use App\Models\banglola\Server;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter dan search
        $status = $request->get('status', 'semua');
        $search = $request->get('search');

        // ===== QUERY WEBSITE =====
        $queryWebsite = Website::with('satker');

        // Filter status untuk website
        if ($status && $status !== 'semua') {
            $queryWebsite->where('status', $status);
        }

        // Search untuk website
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

        // ===== QUERY SERVER =====
        $queryServer = Server::with('satker');

        // Filter status untuk server (mapping ke power_status)
        if ($status && $status !== 'semua') {
            if ($status === 'active') {
                $queryServer->where('power_status', 'ON');
            } elseif ($status === 'maintenance') {
                $queryServer->where('power_status', 'STANDBY');
            } elseif ($status === 'inactive') {
                $queryServer->where('power_status', 'OFF');
            }
        }

        // Search untuk server
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

        // ===== HITUNG RINGKASAN (tanpa filter) =====
        $totalWebsite = Website::count();
        $aktifWebsite = Website::where('status', 'active')->count();
        $maintenanceWebsite = Website::where('status', 'maintenance')->count();
        $tidakAktifWebsite = Website::where('status', 'inactive')->count();

        $totalServer = Server::count();
        $aktifServer = Server::where('power_status', 'ON')->count();
        $maintenanceServer = Server::where('power_status', 'STANDBY')->count();
        $tidakAktifServer = Server::where('power_status', 'OFF')->count();

        // ===== GABUNGKAN DATA =====
        $gabunganData = collect();

        // Tambahkan data website
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

        // Tambahkan data server dengan mapping status
        foreach ($dataServer as $s) {
            // Mapping power_status ke format standar
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

        // Urutkan berdasarkan updated_at terbaru
        $gabunganData = $gabunganData->sortByDesc('updated_at')->values();

        return view('banglola.dashboard', compact(
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
