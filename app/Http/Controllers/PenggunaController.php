<?php

namespace App\Http\Controllers;

use App\Models\superadmin\Bidang;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    // Menampilkan daftar pengguna dengan filter dan search
    public function index(Request $request)
    {
        $query = Pengguna::with('bidang'); // Load relasi bidang

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search berdasarkan nama, username/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username_email', 'like', "%{$search}%");
            });
        }

        $pengguna = $query->paginate(10);

        // Jika request AJAX (untuk filter/search tanpa reload)
        if ($request->ajax()) {
            return response()->json([
                'data' => $pengguna->items(),
                'pagination' => $pengguna->links()->toHtml(),
            ]);
        }

        return view('superadmin.pengguna', compact('pengguna'));
    }

    // Menampilkan form tambah (untuk modal)
    public function create()
    {
        $bidang = Bidang::all();
        return response()->json(['bidang' => $bidang]);
    }

    // Simpan pengguna baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'username_email' => 'required|string|max:100|unique:pengguna,username_email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:superadmin,banglola,pamsis,infratik,tatausaha,pimpinan',
            'bidang_id' => 'required|exists:bidang,bidang_id',
            'status' => 'required|in:active,inactive',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'username_email.required' => 'Username/Email wajib diisi',
            'username_email.unique' => 'Username/Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'bidang_id.required' => 'Bidang wajib dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'status.required' => 'Status wajib dipilih'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            // Create user dengan password yang di-hash
            Pengguna::create([
                'nama_lengkap' => $request->nama_lengkap,
                'username_email' => $request->username_email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'bidang_id' => $request->bidang_id,
                'status' => $request->status,
            ]);

            return redirect()->route('superadmin.pengguna.index')
                           ->with('success', 'Pengguna berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    // Menampilkan data untuk edit (untuk modal)
    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $bidang = Bidang::all();
        return response()->json(['pengguna' => $pengguna, 'bidang' => $bidang]);
    }

    // Update pengguna
    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'username_email' => 'required|string|max:100|unique:pengguna,username_email,' . $id . ',user_id',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:superadmin,banglola,pamsis,infratik,tatausaha,pimpinan',
            'bidang_id' => 'required|exists:bidang,bidang_id',
            'status' => 'required|in:active,inactive',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'username_email.required' => 'Username/Email wajib diisi',
            'username_email.unique' => 'Username/Email sudah digunakan',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
            'bidang_id.required' => 'Bidang wajib dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'status.required' => 'Status wajib dipilih'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            // Siapkan data untuk update
            $data = [
                'nama_lengkap' => $request->nama_lengkap,
                'username_email' => $request->username_email,
                'role' => $request->role,
                'bidang_id' => $request->bidang_id,
                'status' => $request->status,
            ];

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $pengguna->update($data);

            return redirect()->route('superadmin.pengguna.index')
                           ->with('success', 'Data pengguna berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    // Toggle status (aktif/nonaktif)
    public function toggleStatus($id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);
            
            // Toggle status
            $pengguna->status = $pengguna->status == 'active' ? 'inactive' : 'active';
            $pengguna->save();

            $statusText = $pengguna->status == 'active' ? 'aktif' : 'nonaktif';

            return redirect()->route('superadmin.pengguna.index')
                           ->with('success', "Status pengguna berhasil diubah menjadi {$statusText}!");
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Hapus pengguna
    public function destroy($id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);
            
            // Cek apakah user adalah dirinya sendiri (opsional, sesuaikan dengan kebutuhan)
            // if ($pengguna->user_id === auth()->id()) {
            //     return redirect()->back()
            //                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            // }

            $pengguna->delete();

            return redirect()->route('superadmin.pengguna.index')
                           ->with('success', 'Pengguna berhasil dihapus dan dipindahkan ke arsip!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method helper untuk get bidang (jika diperlukan)
    public function getBidang()
    {
        $bidang = Bidang::all();
        return response()->json(['bidang' => $bidang]);
    }
}