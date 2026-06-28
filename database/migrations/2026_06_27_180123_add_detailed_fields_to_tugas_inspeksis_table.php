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
            $table->boolean('ketersediaan_air')->nullable()->after('kondisi_kebersihan');
            $table->boolean('ketersediaan_sabun')->nullable()->after('ketersediaan_air');
            $table->boolean('bau_tidak_sedap')->nullable()->after('ketersediaan_sabun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_inspeksis', function (Blueprint $table) {
            $table->dropColumn(['ketersediaan_air', 'ketersediaan_sabun', 'bau_tidak_sedap']);
        });
    }
};
