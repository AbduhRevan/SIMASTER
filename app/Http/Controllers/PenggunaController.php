<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Pengguna;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    /**
     * Menampilkan daftar pengguna dengan filter dan search
     */
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

        // Order by created_at descending (data terbaru di atas)
        $query->orderBy('created_at', 'desc');

        // Pagination dengan 10 data per halaman
        $pengguna = $query->paginate(10);

        // Append query string untuk pagination agar filter tetap ada
        $pengguna->appends($request->query());

        // Jika request AJAX (untuk filter/search tanpa reload halaman penuh)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $pengguna->items(),
                'pagination' => $pengguna->links()->toHtml(),
                'total' => $pengguna->total(),
                'current_page' => $pengguna->currentPage(),
                'last_page' => $pengguna->lastPage(),
            ]);
        }

        return view('pengguna', compact('pengguna'));
    }

    /**
     * Menampilkan form tambah (untuk modal) - Optional jika diperlukan AJAX
     */
    public function create()
    {
        $bidang = Bidang::orderBy('nama_bidang', 'asc')->get();
        return response()->json([
            'success' => true,
            'bidang' => $bidang
        ]);
    }

    /**
     * Simpan pengguna baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'username_email' => 'required|string|max:100|unique:pengguna,username_email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:superadmin,operator banglola,operator pamsis,operator infratik,operator tatausaha,pimpinan',
            'bidang_id' => 'required|exists:bidang,bidang_id',
            'status' => 'required|in:active,inactive',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.max' => 'Nama lengkap maksimal 100 karakter',
            'username_email.required' => 'Username/Email wajib diisi',
            'username_email.unique' => 'Username/Email sudah terdaftar',
            'username_email.max' => 'Username/Email maksimal 100 karakter',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'bidang_id.required' => 'Bidang wajib dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            // Create user dengan password yang di-hash
            $pengguna = Pengguna::create([
                'nama_lengkap' => $request->nama_lengkap,
                'username_email' => $request->username_email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'bidang_id' => $request->bidang_id,
                'status' => $request->status,
            ]);

            // Get bidang name untuk log
            $bidangNama = Bidang::find($request->bidang_id)->nama_bidang ?? 'Unknown';
            $roleLabel = $this->getRoleLabel($request->role);

            // TAMBAHKAN LOG CREATE
            LogAktivitas::log(
                'CREATE',
                'pengguna',
                "Menambahkan pengguna baru: {$pengguna->nama_lengkap} ({$pengguna->username_email}) dengan role {$roleLabel} di bidang {$bidangNama}",
                Auth::id()
            );

            return redirect()->route('superadmin.pengguna.index')
                ->with('success', 'Pengguna berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error create pengguna: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan data untuk edit (untuk modal) - Optional jika diperlukan AJAX
     */
    public function edit($id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);
            $bidang = Bidang::orderBy('nama_bidang', 'asc')->get();

            return response()->json([
                'success' => true,
                'pengguna' => $pengguna,
                'bidang' => $bidang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update pengguna
     */
    public function update(Request $request, $id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.pengguna.index')
                ->with('error', 'Pengguna tidak ditemukan!');
        }

        // Simpan data lama untuk log
        $namaLama = $pengguna->nama_lengkap;
        $usernameLama = $pengguna->username_email;
        $roleLama = $pengguna->role;
        $bidangLama = $pengguna->bidang_id;
        $statusLama = $pengguna->status;

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'username_email' => 'required|string|max:100|unique:pengguna,username_email,' . $id . ',user_id',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:superadmin,operator banglola,operator pamsis,operator infratik,operator tatausaha,pimpinan',
            'bidang_id' => 'required|exists:bidang,bidang_id',
            'status' => 'required|in:active,inactive',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.max' => 'Nama lengkap maksimal 100 karakter',
            'username_email.required' => 'Username/Email wajib diisi',
            'username_email.unique' => 'Username/Email sudah digunakan pengguna lain',
            'username_email.max' => 'Username/Email maksimal 100 karakter',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'bidang_id.required' => 'Bidang wajib dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
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
            $passwordDiubah = false;
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
                $passwordDiubah = true;
            }

            // Update data pengguna
            $pengguna->update($data);

            // TAMBAHKAN LOG UPDATE - Track perubahan
            $perubahan = [];

            if ($namaLama !== $request->nama_lengkap) {
                $perubahan[] = "nama dari '{$namaLama}' menjadi '{$request->nama_lengkap}'";
            }
            if ($usernameLama !== $request->username_email) {
                $perubahan[] = "username/email dari '{$usernameLama}' menjadi '{$request->username_email}'";
            }
            if ($roleLama !== $request->role) {
                $roleLamaLabel = $this->getRoleLabel($roleLama);
                $roleBaruLabel = $this->getRoleLabel($request->role);
                $perubahan[] = "role dari {$roleLamaLabel} menjadi {$roleBaruLabel}";
            }
            if ($bidangLama != $request->bidang_id) {
                $bidangLamaNama = Bidang::find($bidangLama)->nama_bidang ?? 'Unknown';
                $bidangBaruNama = Bidang::find($request->bidang_id)->nama_bidang ?? 'Unknown';
                $perubahan[] = "bidang dari '{$bidangLamaNama}' menjadi '{$bidangBaruNama}'";
            }
            if ($statusLama !== $request->status) {
                $statusLamaLabel = $statusLama == 'active' ? 'Aktif' : 'Nonaktif';
                $statusBaruLabel = $request->status == 'active' ? 'Aktif' : 'Nonaktif';
                $perubahan[] = "status dari {$statusLamaLabel} menjadi {$statusBaruLabel}";
            }
            if ($passwordDiubah) {
                $perubahan[] = "password diubah";
            }

            $deskripsiPerubahan = count($perubahan) > 0
                ? "Mengupdate pengguna {$request->nama_lengkap}: " . implode(', ', $perubahan)
                : "Mengupdate data pengguna: {$request->nama_lengkap}";

            LogAktivitas::log(
                'UPDATE',
                'pengguna',
                $deskripsiPerubahan,
                Auth::id()
            );

            return redirect()->route('superadmin.pengguna.index')
                ->with('success', 'Data pengguna berhasil diperbarui!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error update pengguna: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Toggle status (aktif/nonaktif)
     */
    public function toggleStatus($id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);

            // Cek apakah user sedang login adalah dirinya sendiri
            if ($pengguna->user_id === Auth::id()) {
                return redirect()->back()
                    ->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri!');
            }

            // Simpan status lama
            $statusLama = $pengguna->status;

            // Toggle status
            $pengguna->status = $pengguna->status == 'active' ? 'inactive' : 'active';
            $pengguna->save();

            $statusText = $pengguna->status == 'active' ? 'aktif' : 'nonaktif';

            // TAMBAHKAN LOG UPDATE
            LogAktivitas::log(
                'UPDATE',
                'pengguna',
                "Mengubah status pengguna {$pengguna->nama_lengkap} dari " .
                    ($statusLama == 'active' ? 'aktif' : 'nonaktif') .
                    " menjadi {$statusText}",
                Auth::id()
            );

            return redirect()->route('superadmin.pengguna.index')
                ->with('success', "Status pengguna berhasil diubah menjadi {$statusText}!");
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error toggle status pengguna: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pengguna (Hard Delete)
     */
    public function destroy($id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);
            $namaPengguna = $pengguna->nama_lengkap;
            $usernamePengguna = $pengguna->username_email;

            // Cek apakah user sedang login adalah dirinya sendiri
            if ($pengguna->user_id === Auth::id()) {
                return redirect()->back()
                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            }

            // Hapus pengguna (Hard Delete)
            $pengguna->delete();

            // TAMBAHKAN LOG DELETE
            LogAktivitas::log(
                'DELETE',
                'pengguna',
                "Menghapus pengguna: {$namaPengguna} ({$usernamePengguna})",
                Auth::id()
            );

            return redirect()->route('superadmin.pengguna.index')
                ->with('success', 'Pengguna berhasil dihapus secara permanen!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error delete pengguna: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Method helper untuk get bidang (untuk AJAX request jika diperlukan)
     */
    public function getBidang()
    {
        try {
            $bidang = Bidang::orderBy('nama_bidang', 'asc')->get();
            return response()->json([
                'success' => true,
                'bidang' => $bidang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data bidang'
            ], 500);
        }
    }

    /**
     * Helper method untuk format role label
     */
    private function getRoleLabel($role)
    {
        return match ($role) {
            'superadmin' => 'Super Admin',
            'operator banglola' => 'Banglola',
            'operator pamsis' => 'Pamsis',
            'operator infratik' => 'Infratik',
            'operator tatausaha' => 'Tata Usaha',
            'pimpinan' => 'Pimpinan',
            default => ucfirst($role)
        };
    }
}
