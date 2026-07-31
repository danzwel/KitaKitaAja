<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data mahasiswa magang aktif, dibuat otomatis saat Admin menyetujui
     * sebuah pengajuan (Approval -> Terima).
     * username & password (hashed) dipakai untuk Login Mahasiswa (modul Raihan).
     * Mohon konfirmasi ke Raihan bahwa guard login mahasiswa mengacu ke tabel ini.
     */
    public function up(): void
    {
        Schema::create('interns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->nullable()->constrained('applications');
            $table->foreignId('department_id')->constrained('departments');

            $table->string('name');
            $table->string('university');
            $table->string('period');
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');

            $table->string('username')->unique();
            $table->string('password'); // hashed

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};
