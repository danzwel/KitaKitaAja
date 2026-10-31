<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internship_documentation_photos', function (Blueprint $table): void {
            // Dokumentasi lama tetap dapat ditampilkan, sedangkan unggahan baru
            // selalu dimiliki oleh mahasiswa yang menyelesaikan magang.
            $table->foreignId('uploaded_by')->nullable()->change();
            $table->foreignId('intern_id')
                ->nullable()
                ->after('uploaded_by')
                ->constrained('interns')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('internship_documentation_photos', function (Blueprint $table): void {
            $table->dropForeign(['intern_id']);
            $table->dropColumn('intern_id');
            $table->foreignId('uploaded_by')->nullable(false)->change();
        });
    }
};
