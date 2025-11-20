<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuan_kerja', function (Blueprint $table) {
            $table->id('satker_id');
            $table->string('nama_satker', 150);
            $table->string('singkatan_satker', 100)->nullable();

            $table->softDeletes();

            $table->unique('nama_satker', 'uk_nama_satker');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satuan_kerja');
    }
};
