<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeliharaan', function (Blueprint $table) {
            // Tambah kolom status pemeliharaan
            $table->enum('status_pemeliharaan', ['dijadwalkan', 'berlangsung', 'selesai', 'dibatalkan'])
                ->default('dijadwalkan')
                ->after('tanggal_pemeliharaan');

            // Tambah kolom untuk menyimpan status sebelumnya
            $table->string('status_sebelumnya', 50)->nullable()->after('status_pemeliharaan');

            // Tambah kolom tanggal selesai aktual
            $table->datetime('tanggal_selesai_aktual')->nullable()->after('status_sebelumnya');

            // Tambah timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('pemeliharaan', function (Blueprint $table) {
            $table->dropColumn([
                'status_pemeliharaan',
                'status_sebelumnya',
                'tanggal_selesai_aktual',
                'created_at',
                'updated_at'
            ]);
        });
    }
};
