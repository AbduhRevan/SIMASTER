<?php

namespace App\Http\Controllers;

use App\Models\superadmin\Bidang;
use App\Models\Pengguna;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            $pengguna = Pengguna::create([
                'nama_lengkap' => $request->nama_lengkap,
                'username_email' => $request->username_email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'bidang_id' => $request->bidang_id,
                'status' => $request->status,
            ]);

            // TAMBAHKAN LOG CREATE
            $bidangNama = Bidang::find($request->bidang_id)->nama_bidang ?? 'Unknown';
            $roleLabel = $this->getRoleLabel($request->role);

            LogAktivitas::log(
                'CREATE',
                'pengguna',
                "Menambahkan pengguna baru: {$pengguna->nama_lengkap} ({$pengguna->username_email}) dengan role {$roleLabel} di bidang {$bidangNama}",
                Auth::id()
            );

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

        // Simpan data lama untuk log
        $namaLama = $pengguna->nama_lengkap;
        $usernameLama = $pengguna->username_email;
        $roleLama = $pengguna->role;
        $bidangLama = $pengguna->bidang_id;
        $statusLama = $pengguna->status;

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
            $passwordDiubah = false;
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
                $passwordDiubah = true;
            }

            $pengguna->update($data);

            // TAMBAHKAN LOG UPDATE
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
                "Mengubah status pengguna {$pengguna->nama_lengkap} menjadi {$statusText}",
                Auth::id()
            );

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
            $namaPengguna = $pengguna->nama_lengkap;
            $usernamePengguna = $pengguna->username_email;

            // Cek apakah user adalah dirinya sendiri (opsional, sesuaikan dengan kebutuhan)
            // if ($pengguna->user_id === auth()->id()) {
            //     return redirect()->back()
            //                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            // }

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

    // Helper method untuk format role label
    private function getRoleLabel($role)
    {
        return match ($role) {
            'superadmin' => 'Super Admin',
            'banglola' => 'Banglola',
            'pamsis' => 'Pamsis',
            'infratik' => 'Infratik',
            'tatausaha' => 'Tata Usaha',
            'pimpinan' => 'Pimpinan',
            default => ucfirst($role)
        };
    }
}
