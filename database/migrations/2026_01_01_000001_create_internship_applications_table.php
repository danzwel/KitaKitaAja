<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();

            $table->string('application_code', 20)->unique();

            $table->string('nama');
            $table->string('nim', 30);
            $table->string('universitas');
            $table->string('fakultas');
            $table->string('program_studi');
            $table->string('semester', 5);
            $table->string('email');
            $table->string('no_hp', 20);
            $table->text('alamat');

            $table->date('periode_mulai');
            $table->date('periode_selesai');

            $table->string('bidang_diminati');

            $table->text('tujuan_magang');

            $table->enum('status', [
                'menunggu_verifikasi',
                'diproses',
                'diterima',
                'ditolak',
            ])->default('menunggu_verifikasi');

            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};