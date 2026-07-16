<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->string('semester_ambil', 20); // contoh: "2024/2025 Ganjil"
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->string('nilai_huruf', 2)->nullable();
            $table->decimal('bobot', 3, 2)->nullable(); // 0.00 - 4.00
            $table->enum('status', ['Lulus', 'Tidak Lulus', 'Mengulang'])->default('Lulus');
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'semester_ambil']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
