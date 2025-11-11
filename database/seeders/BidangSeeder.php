<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidangs = [
            [
                'nama_bidang' => 'Pengembangan dan Pengelolaan Sistem Informasi',
                'singkatan_bidang' => 'BANGLOLA',
            ],
            [
                'nama_bidang' => 'Pengamanan Sistem Informasi dan Persandian',
                'singkatan_bidang' => 'PAMSIS',
            ],
            [
                'nama_bidang' => 'Infrastruktur Teknologi Informasi dan Komunikasi',
                'singkatan_bidang' => 'INFRATIK',
            ],
            [
                'nama_bidang' => 'Bagian Tata Usaha',
                'singkatan_bidang' => 'BAGTU',
            ],
        ];

        foreach ($bidangs as $bidang) {
            DB::table('bidang')->insert([
                'nama_bidang' => $bidang['nama_bidang'],
                'singkatan_bidang' => $bidang['singkatan_bidang'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
