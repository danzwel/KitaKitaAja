<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_documentation_photos', function (Blueprint $table): void {
            $table->id();
            $table->unsignedSmallInteger('year')->index();
            $table->foreignId('uploaded_by')->constrained('admins');
            $table->string('image_path');
            $table->string('caption', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_documentation_photos');
    }
};
