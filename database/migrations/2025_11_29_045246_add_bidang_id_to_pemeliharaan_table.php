<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemeliharaan', function (Blueprint $table) {
            // Tambah kolom bidang_id setelah kolom tertentu (opsional)
            $table->unsignedBigInteger('bidang_id')->nullable()->after('pemeliharaan_id');
            
            // Tambah foreign key constraint
            $table->foreign('bidang_id')
                  ->references('bidang_id')
                  ->on('bidang')
                  ->onDelete('set null') // atau 'cascade' tergantung kebutuhan
                  ->onUpdate('cascade');
                  
            // Tambah index untuk performa query
            $table->index('bidang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeliharaan', function (Blueprint $table) {
            // Drop foreign key dulu
            $table->dropForeign(['bidang_id']);
            
            // Baru drop kolom
            $table->dropColumn('bidang_id');
        });
    }
};