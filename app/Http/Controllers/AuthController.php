<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\LogAktivitas;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username_email' => 'required',
            'password' => 'required',
        ], [
            'username_email.required' => 'Username/Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $pengguna = Pengguna::where('username_email', $request->username_email)->first();

        // Cek apakah user ada
        if (!$pengguna) {
            return back()->withErrors([
                'username_email' => 'Username/Email tidak ditemukan',
            ])->withInput();
        }

        // Cek apakah user aktif
        if (!$pengguna->isActive()) {
            return back()->withErrors([
                'username_email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->withInput();
        }

        // Cek password
        if (!Hash::check($request->password, $pengguna->password)) {
            return back()->withErrors([
                'password' => 'Password salah',
            ])->withInput();
        }

        // Login berhasil
        Auth::login($pengguna, $request->filled('remember'));

        // Log aktivitas login
        $this->logActivity($pengguna->user_id, 'LOGIN', 'pengguna', 'Login berhasil');

        $request->session()->regenerate();

        return $this->redirectBasedOnRole();
    }

    // Logout
    public function logout(Request $request)
    {
        // Log aktivitas logout
        if (Auth::check()) {
            $this->logActivity(Auth::id(), 'LOGOUT', 'pengguna', 'Logout berhasil');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout');
    }

    // Redirect berdasarkan role
    private function redirectBasedOnRole()
    {
        $role = Auth::user()->role;

        return redirect()->route('dashboard');
    }

    // Log aktivitas
    private function logActivity($userId, $aksi, $entitas, $deskripsi)
    {
        try {
            LogAktivitas::create([
                'user_id' => $userId,
                'aksi' => $aksi,
                'entitas_diubah' => $entitas,
                'deskripsi' => $deskripsi,
            ]);
        } catch (\Exception $e) {
            // Silent fail jika tabel log belum ada
        }
    }
}
