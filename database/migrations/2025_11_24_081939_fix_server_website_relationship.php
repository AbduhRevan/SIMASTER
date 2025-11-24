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
        // 1. Hapus foreign key website_id dari tabel server
        Schema::table('server', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign('server_website_id_foreign');
            // Drop index
            $table->dropIndex('idx_server_website');
            // Drop column
            $table->dropColumn('website_id');
        });

        // 2. Tambah kolom server_id ke tabel website
        Schema::table('website', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id')->nullable()->after('satker_id');

            // Tambah foreign key
            $table->foreign('server_id', 'website_server_id_foreign')
                ->references('server_id')
                ->on('server')
                ->onDelete('set null')
                ->onUpdate('cascade');

            // Tambah index
            $table->index('server_id', 'idx_website_server');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan perubahan jika rollback
        Schema::table('website', function (Blueprint $table) {
            $table->dropForeign('website_server_id_foreign');
            $table->dropIndex('idx_website_server');
            $table->dropColumn('server_id');
        });

        Schema::table('server', function (Blueprint $table) {
            $table->unsignedBigInteger('website_id')->nullable();

            $table->foreign('website_id')
                ->references('website_id')
                ->on('website')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->index('website_id', 'idx_server_website');
        });
    }
};
