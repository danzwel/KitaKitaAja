<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_certificates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('intern_id')->unique()->constrained('interns')->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('admins');
            $table->string('certificate_number')->unique();
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_certificates');
    }
};
