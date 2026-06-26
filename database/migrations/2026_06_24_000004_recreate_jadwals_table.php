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
        // Drop inspeksi_photos first since it has foreign key to jadwals
        Schema::dropIfExists('inspeksi_photos');
        
        // Then drop and recreate jadwals
        Schema::dropIfExists('jadwals');
        
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('jenis_tugas', ['Inspeksi Sanitasi', 'Pembersihan Rutin', 'Pemeliharaan'])->default('Inspeksi Sanitasi');
            $table->enum('status', ['Menunggu', 'Berlangsung', 'Selesai'])->default('Menunggu');
            $table->boolean('status_aktif')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['petugas_id', 'fasilitas_id', 'tanggal_mulai'], 'unique_jadwal_inspeksi');
        });

        // Recreate inspeksi_photos
        Schema::create('inspeksi_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade');
            $table->string('foto_path');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspeksi_photos');
        Schema::dropIfExists('jadwals');
        
        // Recreate old structure
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->boolean('status_aktif')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['petugas_id', 'fasilitas_id', 'hari', 'jam_mulai'], 'unique_jadwal_petugas');
        });
    }
};
