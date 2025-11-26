<?php

namespace App\Http\Controllers\tatausaha;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        try {

            // ---------- Statistik untuk Cards ----------
            $totalLog = LogAktivitas::whereHas('pengguna', function ($q) {
                $q->where('role', 'tatausaha');
            })->count();

            $logCreate = LogAktivitas::where('aksi', 'CREATE')
                ->whereHas('pengguna', fn($q) => $q->where('role', 'tatausaha'))
                ->count();

            $logUpdate = LogAktivitas::where('aksi', 'UPDATE')
                ->whereHas('pengguna', fn($q) => $q->where('role', 'tatausaha'))
                ->count();

            $logDelete = LogAktivitas::where('aksi', 'DELETE')
                ->whereHas('pengguna', fn($q) => $q->where('role', 'tatausaha'))
                ->count();


            // ---------- Query utama ----------
            $query = LogAktivitas::with('pengguna')
                ->whereHas('pengguna', function ($q) {
                    $q->where('role', 'tatausaha');
                });


            // Filter Aksi
            if ($request->filled('aksi')) {
                $query->where('aksi', $request->aksi);
            }

            // Filter Entitas
            if ($request->filled('entitas')) {
                $query->where('entitas_diubah', $request->entitas);
            }

            // Filter Tanggal
            if ($request->filled('tanggal')) {
                $query->whereDate('waktu_aksi', $request->tanggal);
            }

            // Searching
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('pengguna', function ($q2) use ($search) {
                            $q2->where('nama_lengkap', 'like', "%{$search}%")
                                ->orWhere('username_email', 'like', "%{$search}%");
                        });
                });
            }


            // Pagination 10 data terbaru
            $logs = $query->orderBy('waktu_aksi', 'desc')->paginate(10);


            // Tampilkan view khusus tatausaha
            return view('tatausaha.logAktivitas', compact(
                'logs',
                'totalLog',
                'logCreate',
                'logUpdate',
                'logDelete'
            ));

        } catch (\Exception $e) {

            return view('tatausaha.logAktivitas', [
                'logs' => collect()->paginate(10),
                'totalLog' => 0,
                'logCreate' => 0,
                'logUpdate' => 0,
                'logDelete' => 0
            ])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
