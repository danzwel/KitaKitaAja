<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu pengajuan yang diterima hanya boleh memiliki satu akun intern.
     * Constraint ini melindungi proses approval dari duplikasi saat terjadi
     * request bersamaan atau retry pada sisi aplikasi.
     */
    public function up(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->unique('application_id', 'interns_application_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->dropUnique('interns_application_id_unique');
        });
    }
};
