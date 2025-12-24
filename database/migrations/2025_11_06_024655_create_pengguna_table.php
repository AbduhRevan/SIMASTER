<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('nama_lengkap', 100);
            $table->string('username_email', 100)->unique();
            $table->string('password', 255);
            $table->enum('role', ['superadmin', 'operator banglola', 'operator pamsis', 'operator infratik', 'operator tatausaha', 'pimpinan'])->default('pimpinan');
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->foreign('bidang_id')
                  ->references('bidang_id')
                  ->on('bidang')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            $table->index('bidang_id', 'idx_pengguna_bidang');
            $table->index('username_email', 'idx_pengguna_username');
            $table->index('status', 'idx_pengguna_status');
            
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};