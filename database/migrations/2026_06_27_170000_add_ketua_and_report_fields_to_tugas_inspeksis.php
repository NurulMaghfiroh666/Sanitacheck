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
            $table->foreignId('ketua_id')->nullable()->after('fasilitas_id')->constrained('users')->onDelete('set null');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('laporan_catatan')->nullable()->after('approval_status');
            $table->string('laporan_foto')->nullable()->after('laporan_catatan');
            $table->enum('kondisi_kebersihan', ['Baik', 'Cukup', 'Buruk'])->nullable()->after('laporan_foto');
            $table->timestamp('laporan_submitted_at')->nullable()->after('kondisi_kebersihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_inspeksis', function (Blueprint $table) {
            $table->dropForeign(['ketua_id']);
            $table->dropColumn(['ketua_id', 'approval_status', 'laporan_catatan', 'laporan_foto', 'kondisi_kebersihan', 'laporan_submitted_at']);
        });
    }
};
