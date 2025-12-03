<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Hitung statistik untuk cards
            $totalLog = LogAktivitas::count();
            $logCreate = LogAktivitas::where('aksi', 'CREATE')->count();
            $logUpdate = LogAktivitas::where('aksi', 'UPDATE')->count();
            $logDelete = LogAktivitas::where('aksi', 'DELETE')->count();

            // Query dengan filter dan relasi ke pengguna
            $query = LogAktivitas::with('pengguna');

            // Filter berdasarkan aksi
            if ($request->filled('aksi')) {
                $query->where('aksi', $request->aksi);
            }

            // Filter berdasarkan entitas
            if ($request->filled('entitas')) {
                $query->where('entitas_diubah', $request->entitas);
            }

            // Filter berdasarkan tanggal
            if ($request->filled('tanggal')) {
                $query->whereDate('waktu_aksi', $request->tanggal);
            }

            // Pencarian
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

            // Urutkan dari yang terbaru dengan 10 data per halaman
            $logs = $query->orderBy('waktu_aksi', 'desc')->paginate(10);

            return view('logAktivitas', compact(
                'logs',
                'totalLog',
                'logCreate',
                'logUpdate',
                'logDelete'
            ));
        } catch (\Exception $e) {
            // Jika ada error, kembalikan dengan data default
            return view('logAktivitas', [
                'logs' => collect()->paginate(10),
                'totalLog' => 0,
                'logCreate' => 0,
                'logUpdate' => 0,
                'logDelete' => 0
            ])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
