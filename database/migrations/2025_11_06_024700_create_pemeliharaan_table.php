<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id('pemeliharaan_id');
            $table->unsignedBigInteger('server_id')->nullable();
            $table->unsignedBigInteger('website_id')->nullable();
            $table->date('tanggal_pemeliharaan');
            $table->text('keterangan')->nullable();
            
            $table->foreign('server_id')
                  ->references('server_id')
                  ->on('server')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->foreign('website_id')
                  ->references('website_id')
                  ->on('website')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            $table->index('server_id', 'idx_pemeliharaan_server');
            $table->index('website_id', 'idx_pemeliharaan_website');
            $table->index('tanggal_pemeliharaan', 'idx_pemeliharaan_tanggal');
            
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        
        // Add CHECK constraint
        DB::statement('ALTER TABLE pemeliharaan ADD CONSTRAINT chk_pemeliharaan CHECK ((server_id IS NOT NULL AND website_id IS NULL) OR (server_id IS NULL AND website_id IS NOT NULL))');
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeliharaan');
    }
};