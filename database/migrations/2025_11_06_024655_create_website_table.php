<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website', function (Blueprint $table) {
            $table->id('website_id');
            $table->string('nama_website', 150);
            $table->string('url', 255);
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->unsignedBigInteger('satker_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->year('tahun_pengadaan')->nullable();
            $table->text('keterangan')->nullable();
            
            $table->foreign('bidang_id')
                  ->references('bidang_id')
                  ->on('bidang')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
                  
            $table->foreign('satker_id')
                  ->references('satker_id')
                  ->on('satuan_kerja')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            $table->unique('url', 'uk_url');
            $table->index('url', 'idx_website_url');
            $table->index('bidang_id', 'idx_website_bidang');
            $table->index('satker_id', 'idx_website_satker');
            $table->index('status', 'idx_website_status');
            
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website');
    }
};