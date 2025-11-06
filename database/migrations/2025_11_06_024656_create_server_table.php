<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server', function (Blueprint $table) {
            $table->id('server_id');
            $table->string('nama_server', 100);
            $table->string('brand', 50)->nullable();
            $table->text('spesifikasi')->nullable();
            $table->enum('power_status', ['ON', 'OFF', 'STANDBY'])->default('OFF');
            $table->unsignedBigInteger('rak_id')->nullable();
            $table->string('u_slot', 20)->nullable();
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->unsignedBigInteger('satker_id')->nullable();
            $table->unsignedBigInteger('website_id')->nullable();
            $table->text('keterangan')->nullable();
            
            $table->foreign('rak_id')
                  ->references('rak_id')
                  ->on('rak_server')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
                  
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
                  
            $table->foreign('website_id')
                  ->references('website_id')
                  ->on('website')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            $table->unique('nama_server', 'uk_nama_server');
            $table->index('nama_server', 'idx_server_nama');
            $table->index('rak_id', 'idx_server_rak');
            $table->index('bidang_id', 'idx_server_bidang');
            $table->index('satker_id', 'idx_server_satker');
            $table->index('website_id', 'idx_server_website');
            $table->index('power_status', 'idx_server_power');
            
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server');
    }
};