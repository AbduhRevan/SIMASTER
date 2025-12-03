<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\Bidang;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan bidang-bidang penting ada (tidak duplikat)
        $bidangBanglola = Bidang::firstOrCreate(
            ['singkatan_bidang' => 'BANGLOLA'],
            ['nama_bidang' => 'Pengembangan dan Pengelolaan Sistem Informasi']
        );

        $bidangPamsis = Bidang::firstOrCreate(
            ['singkatan_bidang' => 'PAMSIS'],
            ['nama_bidang' => 'Pengamanan Sistem Informasi dan Persandian']
        );

        $bidangInfratik = Bidang::firstOrCreate(
            ['singkatan_bidang' => 'INFRATIK'],
            ['nama_bidang' => 'Infrastruktur Teknologi Informasi dan Komunikasi']
        );

        $bidangBagtu = Bidang::firstOrCreate(
            ['singkatan_bidang' => 'BAGTU'],
            ['nama_bidang' => 'Bagian Tata Usaha']
        );

        // Siapkan array user dengan bidang_id yang sesuai (null bila tidak perlu)
        $users = [
            [
                'nama_lengkap' => 'Super Administrator',
                'username_email' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'bidang_id' => null,
                'status' => 'active',
            ],
            [
                'nama_lengkap' => 'Pengembangan dan Pengelolaan Sistem Informasi',
                'username_email' => 'banglola',
                'password' => Hash::make('password'),
                'role' => 'banglola',
                'bidang_id' => $bidangBanglola->bidang_id,
                'status' => 'active',
            ],
            [
                'nama_lengkap' => 'Pengamanan Sistem Informasi dan Persandian',
                'username_email' => 'pamsis',
                'password' => Hash::make('password'),
                'role' => 'pamsis',
                'bidang_id' => $bidangPamsis->bidang_id,
                'status' => 'active',
            ],
            [
                'nama_lengkap' => 'Infrastruktur TIK',
                'username_email' => 'infratik',
                'password' => Hash::make('password'),
                'role' => 'infratik',
                'bidang_id' => $bidangInfratik->bidang_id,
                'status' => 'active',
            ],
            [
                'nama_lengkap' => 'Tata Usaha',
                'username_email' => 'tatausaha',
                'password' => Hash::make('password'),
                'role' => 'tatausaha',
                'bidang_id' => $bidangBagtu->bidang_id, // opsional: bisa null juga
                'status' => 'active',
            ],
            [
                'nama_lengkap' => 'Pimpinan',
                'username_email' => 'pimpinan',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
                'bidang_id' => null,
                'status' => 'active',
            ],
        ];

        foreach ($users as $user) {
            // gunakan updateOrCreate supaya tidak bikin duplikat bila seeder dipanggil ulang
            Pengguna::updateOrCreate(
                ['username_email' => $user['username_email']],
                $user
            );
        }
    }
}
