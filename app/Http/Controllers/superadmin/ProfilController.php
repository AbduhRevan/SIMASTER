<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User; // Pastikan Model User Anda berada di App\Models

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
        $user = Auth::user();

        // Pilih layout bedasarkan role
        $layout = match ($user->role) {
            'superadmin' => 'layouts.app',
            'banglola'   => 'layouts.banglola',
            'pamsis'     => 'layouts.pamsis',
            'infratik'   => 'layouts.infratik',
            'tatausaha'  => 'layouts.tatausaha',
            default      => 'layouts.app', // fallback
        };

        return view('profil.profil', compact('user', 'layout'));

    } catch (\Exception $e) {
        return back()->with('error', 'Gagal memuat profil: ' . $e->getMessage());
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
     * Menampilkan halaman ganti password.
     * Route: GET /ganti-password
     * Name: ganti.password
     *
     * @return \Illuminate\View\View
     */
   public function gantiPassword()
{
    return view('profil.ganti_password');
}

/**
 * Proses penyimpanan password baru dengan validasi lengkap dan auto logout.
 * 
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function updatePassword(Request $request)
{
    // Validasi input dengan aturan password yang ketat
    $request->validate([
        'current_password' => [
            'required',
            function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Password lama yang Anda masukkan salah.');
                }
            }
        ],
        'new_password' => [
            'required',
            'min:8',
            'confirmed',
            'regex:/[A-Z]/',      // Harus ada huruf besar
            'regex:/[a-z]/',      // Harus ada huruf kecil
            'regex:/[0-9]/',      // Harus ada angka
        ],
    ], [
        'new_password.min' => 'Password baru harus minimal 8 karakter.',
        'new_password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    try {
        $user = Auth::user();
        
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Logout user dari semua session
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
    }
}


    /**
     * Menampilkan halaman panduan pengguna.
     * Route: GET /panduan-pengguna
     * Name: panduan.pengguna
     *
     * @return \Illuminate\View\View
     */
   
}