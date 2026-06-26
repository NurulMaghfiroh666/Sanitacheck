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
        // Drop foreign key constraint
        Schema::table('inspeksis', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
        });

        // Modify the column to reference petugas table instead of users
        Schema::table('inspeksis', function (Blueprint $table) {
            $table->dropColumn('petugas_id');
        });

        Schema::table('inspeksis', function (Blueprint $table) {
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade')->after('fasilitas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to users table
        Schema::table('inspeksis', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
        });

        Schema::table('inspeksis', function (Blueprint $table) {
            $table->dropColumn('petugas_id');
        });

        Schema::table('inspeksis', function (Blueprint $table) {
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade')->after('fasilitas_id');
        });
    }
};
