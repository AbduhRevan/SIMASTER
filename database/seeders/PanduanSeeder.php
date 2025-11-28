<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPanduan;
use App\Models\ItemPanduan;

class PanduanSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori 1: Informasi Umum
        $infoUmum = KategoriPanduan::create([
            'nama_kategori' => 'Informasi Umum',
            'slug' => 'informasi-umum',
            'deskripsi' => 'Informasi dasar tentang SIMASTER',
            'urutan' => 1,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $infoUmum->id,
            'judul' => 'Apa itu SIMASTER?',
            'konten' => '<p>SIMASTER (Sistem Informasi Manajemen Aset Terpadu) adalah platform berbasis web untuk memfasilitasi pengelolaan aset Teknologi Informasi (TI) secara komprehensif, meliputi inventaris Server, Website, dan kegiatan Pemeliharaan, di lingkungan Pusat Data dan Informasi (Pusdatin) Kementerian Pertahanan.</p>',
            'urutan' => 1,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $infoUmum->id,
            'judul' => 'SIMASTER bertujuan untuk',
            'konten' => '<ul>
                <li>Mengelola inventaris aset TI secara terpusat, akurat, dan real-time.</li>
                <li>Memantau status dan kondisi aset untuk memastikan ketersediaan layanan.</li>
                <li>Menjadwalkan dan mencatat riwayat pemeliharaan.</li>
                <li>Menghasilkan laporan dan analisis pendukung keputusan.</li>
            </ul>',
            'urutan' => 2,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $infoUmum->id,
            'judul' => 'Fitur-Fitur Utama',
            'konten' => '<p>SIMASTER terbagi menjadi beberapa modul fungsional utama:</p>
            <ul>
                <li><strong>Data Master:</strong> Pengelolaan data referensi seperti Bidang, Satuan Kerja (Satker), dan Rak Server.</li>
                <li><strong>Manajemen Aset:</strong> Inventarisasi dan pengelolaan detail aset Server dan Website.</li>
                <li><strong>Manajemen Pemeliharaan:</strong> Penjadwalan, pencatatan, dan pemantauan aktivitas pemeliharaan aset.</li>
                <li><strong>Manajemen Sistem:</strong> Pengelolaan akun Pengguna, penentuan hak akses, dan pemantauan Log Aktivitas.</li>
                <li><strong>Sistem Monitoring:</strong> Dashboard visual, Log Aktivitas, dan Laporan Aset.</li>
            </ul>',
            'urutan' => 3,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $infoUmum->id,
            'judul' => 'Hak Akses Pengguna',
            'konten' => '<p>SIMASTER menerapkan sistem hak akses bertingkat untuk membatasi fungsionalitas sesuai peran pengguna:</p>
            
            <p><strong>1. Super Admin</strong></p>
            <ul>
                <li>Hak akses penuh (Create, Read, Update, Delete) ke seluruh fitur dan data sistem.</li>
                <li>Bertanggung jawab mengelola Data Master, seluruh aset, dan Akun Pengguna.</li>
            </ul>

            <p><strong>2. Admin Bidang</strong></p>
            <ul>
                <li>Hak akses (CRUD) hanya pada aset yang berada di bawah lingkup Bidang yang menjadi tanggung jawabnya.</li>
                <li>Dapat melihat laporan yang spesifik untuk Bidangnya.</li>
            </ul>

            <p><strong>3. Pimpinan</strong></p>
            <ul>
                <li>Hak akses terbatas (View Only).</li>
                <li>Dirancang untuk memantau Dashboard monitoring.</li>
                <li>Dapat melihat seluruh laporan aset tanpa kemampuan mengubah data.</li>
            </ul>',
            'urutan' => 4,
            'is_active' => true,
        ]);

        // Kategori 2: Manajemen Data Master
        $dataMaster = KategoriPanduan::create([
            'nama_kategori' => 'Manajemen Data Master',
            'slug' => 'manajemen-data-master',
            'deskripsi' => 'Panduan mengelola data master sistem',
            'urutan' => 2,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $dataMaster->id,
            'judul' => 'Pengenalan Data Master',
            'konten' => '<p>Modul ini digunakan untuk mengelola data referensi yang dibutuhkan dalam proses inventarisasi aset TI.</p>
            <p>Data yang dikelola mencakup Bidang, Satuan Kerja, dan Rak Server.</p>',
            'urutan' => 1,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $dataMaster->id,
            'judul' => 'Cara Mengelola Bidang',
            'konten' => '<p><strong>Menambah Bidang:</strong></p>
            <ol>
                <li>Akses menu <strong>Data Master &gt; Bidang</strong>.</li>
                <li>Klik tombol <strong>Tambah Bidang</strong>.</li>
                <li>Isi data: Nama Bidang, Singkatan.</li>
                <li>Klik <strong>Simpan</strong>.</li>
            </ol>

            <p><strong>Memperbarui Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Pensil</strong> pada baris data yang ingin diubah.</li>
                <li>Perbarui informasi sesuai kebutuhan.</li>
                <li>Klik <strong>Simpan Perubahan</strong>.</li>
            </ul>

            <p><strong>Menghapus Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Sampah</strong> pada baris data untuk menghapus secara permanen dari sistem.</li>
            </ul>',
            'urutan' => 2,
            'is_active' => true,
        ]);

        // Kategori 3: Manajemen Aset
        $aset = KategoriPanduan::create([
            'nama_kategori' => 'Manajemen Aset',
            'slug' => 'manajemen-aset',
            'deskripsi' => 'Panduan mengelola aset server dan website',
            'urutan' => 3,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $aset->id,
            'judul' => 'Manajemen Aset Server',
            'konten' => '<p>Antarmuka Server dilengkapi dengan card ringkasan (Total Server, Aktif, Maintenance, Tidak Aktif) dan fitur Search untuk pencarian cepat.</p>',
            'urutan' => 1,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $aset->id,
            'judul' => 'Cara Mengelola Server',
            'konten' => '<p><strong>Menambah Server:</strong></p>
            <ol>
                <li>Akses menu <strong>Manajemen Aset &gt; Server</strong>.</li>
                <li>Klik <strong>Tambah Server</strong>.</li>
                <li>Isi Formulir: Nama Server, IP, Brand, Status, Spesifikasi, Rak Server, Satuan Kerja, dan Keterangan (Opsional).</li>
                <li>Pilih Bidang jika Satuan Kerja adalah Pusat Data dan Informasi (Pusdatin).</li>
                <li>Klik <strong>Simpan</strong>.</li>
            </ol>

            <p><strong>Melihat Detail Server:</strong></p>
            <ul>
                <li>Klik ikon <strong>Detail</strong> pada baris data untuk melihat detail lengkap server terkait.</li>
            </ul>

            <p><strong>Memperbarui Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Edit</strong> pada baris data yang ingin diubah, perbarui informasi, lalu klik <strong>Simpan Perubahan</strong>.</li>
            </ul>

            <p><strong>Menghapus Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Delete</strong> untuk menghapus data secara permanen dari sistem.</li>
            </ul>',
            'urutan' => 2,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $aset->id,
            'judul' => 'Manajemen Aset Website',
            'konten' => '<p>Antarmuka Website dilengkapi dengan card ringkasan (Total Website, Aktif, Maintenance, Tidak Aktif) dan fitur Search.</p>',
            'urutan' => 3,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $aset->id,
            'judul' => 'Cara Mengelola Website',
            'konten' => '<p><strong>Menambah Website:</strong></p>
            <ol>
                <li>Akses menu <strong>Manajemen Aset &gt; Website</strong>.</li>
                <li>Klik <strong>Tambah Website</strong>.</li>
                <li>Isi Formulir: Nama, URL, Server, Satuan Kerja, dan Keterangan (Opsional).</li>
                <li>Pilih Bidang jika Satuan Kerja adalah Pusat Data dan Informasi (Pusdatin).</li>
                <li>Klik <strong>Simpan</strong>.</li>
            </ol>

            <p><strong>Melihat Detail Website:</strong></p>
            <ul>
                <li>Klik ikon <strong>Detail</strong> pada baris data untuk melihat detail lengkap website terkait.</li>
            </ul>

            <p><strong>Memperbarui Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Edit</strong> pada baris data yang ingin diubah, perbarui informasi, lalu klik <strong>Simpan Perubahan</strong>.</li>
            </ul>

            <p><strong>Menghapus Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Delete</strong> pada baris data untuk menghapus data secara permanen dari sistem.</li>
            </ul>',
            'urutan' => 4,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $aset->id,
            'judul' => 'Manajemen Pemeliharaan',
            'konten' => '<p>Antarmuka Pemeliharaan dilengkapi dengan card ringkasan (Total Pemeliharaan, Server, Website, Berlangsung) serta fitur Search dan Filter berdasarkan Jenis, Status, dan Tanggal.</p>',
            'urutan' => 5,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $aset->id,
            'judul' => 'Cara Mengelola Pemeliharaan',
            'konten' => '<p><strong>Menambah Jadwal Pemeliharaan:</strong></p>
            <ol>
                <li>Akses menu <strong>Manajemen Aset &gt; Pemeliharaan</strong>.</li>
                <li>Klik <strong>Tambah Jadwal</strong>.</li>
                <li>Isi Formulir: Tanggal Pemeliharaan, Jenis Aset (Server/Website), dan Keterangan (Opsional).</li>
                <li>Klik <strong>Simpan</strong>.</li>
            </ol>

            <p><strong>Melihat Detail Pemeliharaan:</strong></p>
            <ul>
                <li>Klik ikon <strong>Detail</strong> pada baris data untuk melihat detail lengkap jadwal pemeliharaan terkait.</li>
            </ul>

            <p><strong>Memperbarui Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Edit</strong> pada baris data yang ingin diubah, perbarui informasi, lalu klik <strong>Simpan Perubahan</strong>.</li>
            </ul>

            <p><strong>Menghapus Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Delete</strong> untuk menghapus data secara permanen dari sistem.</li>
            </ul>',
            'urutan' => 6,
            'is_active' => true,
        ]);

        // Kategori 4: Manajemen Sistem
        $sistem = KategoriPanduan::create([
            'nama_kategori' => 'Manajemen Sistem',
            'slug' => 'manajemen-sistem',
            'deskripsi' => 'Panduan mengelola pengguna dan log aktivitas',
            'urutan' => 4,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $sistem->id,
            'judul' => 'Pengguna',
            'konten' => '<p>Modul ini digunakan untuk mendaftarkan dan mengatur hak akses pengguna sistem. Terdapat fitur Search dan Filter berdasarkan Role dan Status pengguna.</p>',
            'urutan' => 1,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $sistem->id,
            'judul' => 'Cara Mengelola Pengguna (Fitur khusus Super Admin)',
            'konten' => '<p><strong>Menambah Pengguna:</strong></p>
            <ol>
                <li>Akses menu <strong>Manajemen Sistem &gt; Pengguna</strong>.</li>
                <li>Klik tombol <strong>Tambah Pengguna</strong>.</li>
                <li>Isi Formulir: Nama, Username/Email, Password, Konfirmasi password, Role, Bidang, dan Status.</li>
                <li>Klik <strong>Simpan</strong>.</li>
            </ol>

            <p><strong>Memperbarui Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Edit</strong> pada baris data yang ingin diubah, perbarui informasi, lalu klik <strong>Simpan Perubahan</strong>.</li>
            </ul>

            <p><strong>Menonaktifkan Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Nonaktifkan</strong> untuk mengubah Status Pengguna dari Aktif menjadi Nonaktif.</li>
            </ul>

            <p><strong>Menghapus Data:</strong></p>
            <ul>
                <li>Klik ikon <strong>Delete</strong> untuk menghapus data secara permanen dari sistem.</li>
            </ul>',
            'urutan' => 2,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $sistem->id,
            'judul' => 'Log Aktivitas',
            'konten' => '<p>Log Aktivitas mencatat setiap aksi yang dilakukan oleh pengguna di dalam sistem.</p>',
            'urutan' => 3,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $sistem->id,
            'judul' => 'Cara Mengakses Log Aktivitas',
            'konten' => '<ol>
                <li>Akses menu <strong>Manajemen Sistem &gt; Log Aktivitas</strong>.</li>
                <li>Halaman menampilkan card ringkasan (Total aktivitas, Create, Update, dan Delete).</li>
                <li>Gunakan fitur <strong>Search</strong> untuk pencarian data.</li>
                <li>Gunakan <strong>Filter</strong> berdasarkan Aksi (Create, Update, Delete, Login, Logout, View), Entitas (Pengguna, Server, Website, Pemeliharaan, Bidang, Satker), dan Tanggal spesifik.</li>
                <li>Data Log menampilkan detail: User yang login, tanggal, dan waktu.</li>
                <li>Tersedia tombol <strong>Export Log</strong> dan <strong>Clear Log</strong>.</li>
            </ol>',
            'urutan' => 4,
            'is_active' => true,
        ]);

        // Kategori 5: Manajemen Akun
        $akun = KategoriPanduan::create([
            'nama_kategori' => 'Manajemen Akun',
            'slug' => 'manajemen-akun',
            'deskripsi' => 'Panduan mengelola profil dan password',
            'urutan' => 5,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $akun->id,
            'judul' => 'Cara Melihat dan Memperbarui Foto Profil',
            'konten' => '<p><strong>Melihat Profil:</strong></p>
            <ol>
                <li>Klik ikon <strong>Profil</strong> di sudut kanan atas.</li>
                <li>Pilih <strong>Profil Saya</strong>.</li>
                <li>Halaman menampilkan informasi Pengguna: Foto, Nama Lengkap, Username/Email, Role Pengguna, Bidang, No. Telepon, dan Tanggal Terdaftar Sejak.</li>
            </ol>

            <p><strong>Memperbarui Foto Profil:</strong></p>
            <ol>
                <li>Klik Foto Profil yang ada.</li>
                <li>Pilih <strong>Upload Foto</strong>.</li>
                <li>Pilih file gambar (Format: JPG/PNG, Maksimal: 2MB).</li>
                <li>Lakukan Crop (pemotongan) jika diperlukan.</li>
                <li>Klik <strong>Simpan</strong>.</li>
            </ol>',
            'urutan' => 1,
            'is_active' => true,
        ]);

        ItemPanduan::create([
            'kategori_panduan_id' => $akun->id,
            'judul' => 'Cara Mengganti Kata Sandi (Password)',
            'konten' => '<p><strong>Prosedur Ganti Password:</strong></p>
            <ol>
                <li>Klik ikon <strong>Profil</strong> di sudut kanan atas.</li>
                <li>Pilih <strong>Ganti Password</strong>.</li>
                <li>Isi Formulir: Password Lama, Password Baru, dan Konfirmasi Password.</li>
                <li>Klik <strong>Simpan</strong>.</li>
                <li>Pengguna akan otomatis logout dan harus login kembali dengan password baru.</li>
            </ol>

            <p><strong>Persyaratan Kata Sandi:</strong></p>
            <ol>
                <li>Minimal 8 karakter.</li>
                <li>Mengandung huruf besar (A–Z).</li>
                <li>Mengandung huruf kecil (a–z).</li>
                <li>Mengandung angka (0–9).</li>
            </ol>',
            'urutan' => 2,
            'is_active' => true,
        ]);
    }
}
