<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GantiPasswordController extends Controller
{
    /**
     * Menampilkan halaman ganti password.
     * Route: GET /ganti-password
     * Name: ganti.password
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('profil.ganti_password');
    }

    /**
     * Proses penyimpanan password baru dengan validasi lengkap dan auto logout.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
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
}
