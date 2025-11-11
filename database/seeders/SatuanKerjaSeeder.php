<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuanKerja = [
            [
                'nama_satker' => 'Badan Pendidikan dan Pelatihan Kementerian Pertahanan',
                'singkatan_satker' => 'Badiklat Kemhan',
            ],
            [
                'nama_satker' => 'Badan Instalasi Strategis Pertahanan Kementerian Pertahanan',
                'singkatan_satker' => 'Bainstrahan Kemhan',
            ],
            [
                'nama_satker' => 'Badan Penelitian dan Pengembangan Kementerian Pertahanan',
                'singkatan_satker' => 'Balitbang Kemhan',
            ],
            [
                'nama_satker' => 'Biro Hukum Sekretariat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Biro Hukum Setjen Kemhan',
            ],
            [
                'nama_satker' => 'Biro Hubungan Masyarakat Sekretariat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Biro Humas Setjen Kemhan',
            ],
            [
                'nama_satker' => 'Biro Kepegawaian Sekretariat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Biro Kepegawaian Setjen Kemhan',
            ],
            [
                'nama_satker' => 'Biro Organisasi dan Tata Laksana Sekretariat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Biro Ortala Setjen Kemhan',
            ],
            [
                'nama_satker' => 'Biro Peraturan Perundang-undangan Sekretariat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Biro Peraturan Perundang-undangan Setjen Kemhan',
            ],
            [
                'nama_satker' => 'Biro Perencanaan dan Keuangan Sekretariat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Biro Perencanaan Keuangan Setjen Kemhan',
            ],
            [
                'nama_satker' => 'Biro Tata Usaha dan Protokol Sekretariat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Biro TU dan Protokol Setjen Kemhan',
            ],
            [
                'nama_satker' => 'Biro Umum Sekretariat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Biro Umum Setjen Kemhan',
            ],
            [
                'nama_satker' => 'Direktorat Jenderal Kekuatan Pertahanan Kementerian Pertahanan',
                'singkatan_satker' => 'Ditjen Kuathan Kemhan',
            ],
            [
                'nama_satker' => 'Direktorat Jenderal Potensi Pertahanan',
                'singkatan_satker' => 'Ditjen Pothan Kemhan',
            ],
            [
                'nama_satker' => 'Direktorat Jenderal Rencana Pertahanan Kementerian Pertahanan',
                'singkatan_satker' => 'Ditjen Ranhan Kemhan',
            ],
            [
                'nama_satker' => 'Direktorat Jenderal Strategi Pertahanan Kementerian Pertahanan',
                'singkatan_satker' => 'Ditjen Strahan Kemhan',
            ],
            [
                'nama_satker' => 'Inspektorat Jenderal Kementerian Pertahanan',
                'singkatan_satker' => 'Inspektorat Jenderal',
            ],
            [
                'nama_satker' => 'Pusat Pengendalian dan Informasi Kementerian Pertahanan',
                'singkatan_satker' => 'Pusdalin Kemhan',
            ],
            [
                'nama_satker' => 'Pusat Data dan Informasi Kementerian Pertahanan',
                'singkatan_satker' => 'Pusdatin Kemhan',
            ],
            [
                'nama_satker' => 'Pusat Validasi dan Sertifikasi Alat Peralatan Pertahanan Kementerian Pertahanan',
                'singkatan_satker' => 'Puslaik Kemhan',
            ],
            [
                'nama_satker' => 'Pusat Rehabilitasi Kementerian Pertahanan',
                'singkatan_satker' => 'Pusrehab Kemhan',
            ],
            [
                'nama_satker' => 'Universitas Pertahanan Republik Indonesia',
                'singkatan_satker' => 'Universitas Pertahanan',
            ],
        ];

        foreach ($satuanKerja as $satker) {
            // Pastikan singkatan tidak lebih dari 50 karakter
            $singkatan = $satker['singkatan_satker'];
            if (strlen($singkatan) > 50) {
                $singkatan = substr($singkatan, 0, 50);
            }

            DB::table('satuan_kerja')->insert([
                'nama_satker' => $satker['nama_satker'],
                'singkatan_satker' => $singkatan,
            ]);
        }
    }
}
