<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('internship_application_id')
                ->constrained('internship_applications')
                ->cascadeOnDelete();

            $table->string('surat_pengantar');
            $table->string('cv')->nullable();
            $table->string('proposal')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};