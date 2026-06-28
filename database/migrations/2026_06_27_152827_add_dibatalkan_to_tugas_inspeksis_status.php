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
        Schema::table('tugas_inspeksis', function (Blueprint $table) {
            $table->enum('status', ['tersedia', 'penuh', 'selesai', 'dibatalkan'])->default('tersedia')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_inspeksis', function (Blueprint $table) {
            $table->enum('status', ['tersedia', 'penuh', 'selesai'])->default('tersedia')->change();
        });
    }
};
