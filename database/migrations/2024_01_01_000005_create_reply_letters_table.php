<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Surat Balasan (PDF) yang diunggah Admin setelah pengajuan diterima.
     * Diunduh mahasiswa melalui dashboard mereka (modul Raihan).
     */
    public function up(): void
    {
        Schema::create('reply_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('interns');
            $table->foreignId('uploaded_by')->constrained('admins');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reply_letters');
    }
};
