<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    // =========================
    // TAMPILKAN PROFIL SAYA
    // =========================
    public function profilSaya()
    {
        try {
            $user = Auth::user();
            return view('profil.profil', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat profil: ' . $e->getMessage());
        }
    }


    // =========================
    // UPDATE DATA PROFIL
    // =========================
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username_email' => [
                'required',
                'email',
                Rule::unique('pengguna', 'username_email')->ignore($user->user_id, 'user_id'),
            ],
        ]);

        try {
            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'username_email' => $request->username_email,
            ]);

            return redirect()->route('profil.saya')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan perubahan profil: ' . $e->getMessage());
        }
    }


    // =========================
    // UPLOAD FOTO PROFIL
    // =========================
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:4096'
        ]);

        try {
            $user = Auth::user();

            // Hapus foto lama
            if ($user->foto && file_exists(public_path('uploads/pengguna/' . $user->foto))) {
                unlink(public_path('uploads/pengguna/' . $user->foto));
            }

            // Simpan foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/pengguna'), $filename);

            $user->foto = $filename;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Foto profil berhasil diperbarui.',
                'foto_url' => asset('uploads/pengguna/' . $filename)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal upload foto: ' . $e->getMessage()
            ], 500);
        }
    }


    // =========================
    // HAPUS FOTO PROFIL
    // =========================
    public function hapusFoto()
    {
        try {
            $user = Auth::user();

            if ($user->foto && file_exists(public_path('uploads/pengguna/' . $user->foto))) {
                @unlink(public_path('uploads/pengguna/' . $user->foto));
            }

            $user->foto = null;
            $user->save();

            return back()->with('success', 'Foto profil berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }


    // =========================
    // HALAMAN PANDUAN PENGGUNA
    // =========================
    public function panduanPengguna()
    {
        return view('profil.panduan_pengguna');
    }
}
