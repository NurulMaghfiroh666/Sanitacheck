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
        Schema::table('tugas_inspeksi_user', function (Blueprint $table) {
            $table->foreignId('voted_ketua_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_inspeksi_user', function (Blueprint $table) {
            $table->dropForeign(['voted_ketua_id']);
            $table->dropColumn('voted_ketua_id');
        });
    }
};
