<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('aksi', ['CREATE', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT', 'VIEW', 'EXPORT']);
            $table->enum('entitas_diubah', ['server', 'website', 'pengguna', 'bidang', 'satuan_kerja', 'rak_server', 'pemeliharaan']);
            $table->text('deskripsi')->nullable();
            $table->timestamp('waktu_aksi')->useCurrent();
            
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('pengguna')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            $table->index('user_id', 'idx_log_user');
            $table->index('waktu_aksi', 'idx_log_waktu');
            $table->index('aksi', 'idx_log_aksi');
            $table->index('entitas_diubah', 'idx_log_entitas');
            
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};