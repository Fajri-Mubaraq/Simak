<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('jurusan', 100);
            $table->string('program_studi', 100);
            $table->year('angkatan');
            $table->string('email', 100)->nullable()->unique();
            $table->string('no_telp', 20)->nullable();
            $table->string('alamat')->nullable();
            $table->enum('status', ['Aktif', 'Cuti', 'Lulus', 'DO'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
