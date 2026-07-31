<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PENTING - PERLU KOORDINASI TIM:
     * Tabel ini diisi pertama kali oleh "Form Pengajuan Magang" (modul Sofi),
     * lalu dikelola/di-approve oleh modul Admin (Daniel).
     * Skema di bawah adalah USULAN. Mohon konfirmasi ke Sofi sebelum merge
     * ke branch develop, terutama untuk nama kolom dokumen upload
     * (cover_letter_path, cv_path, proposal_path) agar sinkron dengan
     * fitur "Upload Dokumen" miliknya, supaya tidak terjadi conflict migration.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments');

            // Identitas mahasiswa & kampus (diisi dari Form Pengajuan)
            $table->string('name');
            $table->string('nim');
            $table->string('university');
            $table->string('major');
            $table->string('period'); // contoh: "Jan 2026 - Mar 2026"
            $table->date('application_date');

            // Dokumen (path disimpan via Laravel Storage)
            $table->string('cover_letter_path')->nullable(); // Surat Pengantar
            $table->string('cv_path')->nullable();
            $table->string('proposal_path')->nullable();

            // Status & approval (dikelola Admin)
            $table->enum('status', ['menunggu', 'diproses', 'diterima', 'ditolak'])
                ->default('menunggu');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('admins');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
