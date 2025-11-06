<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rak_server', function (Blueprint $table) {
            $table->id('rak_id');
            $table->string('nomor_rak', 50)->unique();
            $table->string('ruangan', 100);
            $table->integer('kapasitas_u_slot');
            $table->text('keterangan')->nullable();
            
            $table->index('ruangan', 'idx_rak_ruangan');
        });
        
        // Tambahkan CHECK constraint setelah tabel dibuat
        DB::statement('ALTER TABLE rak_server ADD CONSTRAINT chk_kapasitas CHECK (kapasitas_u_slot > 0 AND kapasitas_u_slot <= 50)');
        
        // Set engine dan charset
        DB::statement('ALTER TABLE rak_server ENGINE = InnoDB');
        DB::statement('ALTER TABLE rak_server CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rak_server');
    }
};