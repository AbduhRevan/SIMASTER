<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Bidang; // Jika perlu untuk dropdown bidang
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
        if ($request->has('role') && $request->role != 'Semua') {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != 'Semua') {
            $status = $request->status == 'Aktif' ? 'active' : 'inactive';
            $query->where('status', $status);
        }

        // Search berdasarkan nama, username/email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username_email', 'like', "%{$search}%");
            });
        }

        $pengguna = $query->paginate(10); // Pagination, sesuaikan jika perlu

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
        $bidang = Bidang::all(); // Untuk dropdown bidang
        return response()->json(['bidang' => $bidang]);
    }

    // Simpan pengguna baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'username_email' => 'required|string|max:100|unique:pengguna,username_email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,banglola,pamsis,infratik,tatausaha,pimpinan',
            'bidang_id' => 'nullable|exists:bidang,bidang_id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Pengguna::create($request->all());

        return response()->json(['message' => 'Pengguna berhasil ditambahkan']);
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
            'password' => 'nullable|string|min:8', // Password opsional saat update
            'role' => 'required|in:superadmin,banglola,pamsis,infratik,tatausaha,pimpinan',
            'bidang_id' => 'nullable|exists:bidang,bidang_id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pengguna->update($data);

        return response()->json(['message' => 'Pengguna berhasil diupdate']);
    }

    // Toggle status (aktif/nonaktif)
    public function toggleStatus($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->status = $pengguna->status == 'active' ? 'inactive' : 'active';
        $pengguna->save();

        return response()->json(['message' => 'Status berhasil diubah']);
    }

    // Hapus pengguna
    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->delete();

        return response()->json(['message' => 'Pengguna berhasil dihapus']);
    }
}