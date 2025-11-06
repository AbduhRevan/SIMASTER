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
        // Buat bidang terlebih dahulu
        $bidangInfratik = Bidang::create([
            'nama_bidang' => 'Infrastruktur TIK',
            'singkatan_bidang' => 'INFRATIK',
        ]);

        // Buat pengguna untuk setiap role
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
                'bidang_id' => $bidangInfratik->bidang_id,
                'status' => 'active',
            ],
            [
                'nama_lengkap' => 'Pengamanan Sistem Informasi dan Persandian',
                'username_email' => 'pamsis',
                'password' => Hash::make('password'),
                'role' => 'pamsis',
                'bidang_id' => $bidangInfratik->bidang_id,
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
                'bidang_id' => null,
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
            Pengguna::create($user);
        }
    }
}