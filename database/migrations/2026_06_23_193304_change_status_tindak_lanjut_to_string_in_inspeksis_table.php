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
        DB::statement("ALTER TABLE inspeksis MODIFY COLUMN status_tindak_lanjut VARCHAR(255) DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE inspeksis MODIFY COLUMN status_tindak_lanjut ENUM('aman', 'perlu dibersihkan', 'perlu perbaikan') DEFAULT 'aman'");
    }
};
