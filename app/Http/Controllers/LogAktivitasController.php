<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Base query untuk filter user
            $baseQuery = LogAktivitas::query();
            
            // Filter berdasarkan role/bidang - terapkan di semua query
            if (Auth()->user()->user_id != '1') {
                $id_user = Auth()->user()->user_id;
                $baseQuery->where('user_id', $id_user);
            }

            // Hitung statistik untuk cards (sudah terfilter per user)
            $totalLog = (clone $baseQuery)->count();
            $logCreate = (clone $baseQuery)->where('aksi', 'CREATE')->count();
            $logUpdate = (clone $baseQuery)->where('aksi', 'UPDATE')->count();
            $logDelete = (clone $baseQuery)->where('aksi', 'DELETE')->count();

            // Query untuk list dengan relasi ke pengguna
            $query = (clone $baseQuery)->with('pengguna');
        
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
            return view('logAktivitas', [
                'logs' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1),
                'totalLog' => 0,
                'logCreate' => 0,
                'logUpdate' => 0,
                'logDelete' => 0
            ])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}