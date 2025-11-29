<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User; 

class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil user yang sedang login.
     * Route: GET /profil-saya
     * Name: profil.saya
     *
     * @return \Illuminate\View\View
     */
    public function profilSaya()
    {
        try {
            // Ambil data user yang sedang login
            $user = Auth::user();

            // Tampilkan view profil dengan data user
            // Asumsi view ada di 'profile/profil.blade.php'
            return view('profil.profil', compact('user'));
        } catch (\Exception $e) {
            // Handle jika terjadi error saat pengambilan data
            return redirect()->back()->with('error', 'Gagal memuat profil: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan perubahan data profil (Opsional - Jika ada fungsi Edit).
     * Route: PUT/POST /profil-saya/update
     * Name: profil.update (jika ditambahkan)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // PENTING: Untuk mengaktifkan fungsi ini, Anda harus menambahkan rute PUT/POST di web.php
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username_email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Tambahkan field lain yang bisa diubah, misalnya 'bidang_id', 'satker_id'
        ]);

        try {
            // 2. Update Data User
            $user->nama_lengkap = $request->input('nama_lengkap');
            $user->username_email = $request->input('username_email');
            // $user->bidang_id = $request->input('bidang_id'); // Contoh jika ada field ini
            $user->save();

            return redirect()->route('profil.saya')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan perubahan profil: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman panduan pengguna.
     * Route: GET /panduan-pengguna
     * Name: panduan.pengguna
     *
     * @return \Illuminate\View\View
     */
    public function panduanPengguna()
    {
        // Asumsi view ada di 'profile/panduanPengguna.blade.php'
        return view('profil.panduan_pengguna');
    }
}