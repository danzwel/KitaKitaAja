<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interns', function (Blueprint $table): void {
            $table->foreignId('internship_application_id')
                ->nullable()
                ->after('application_id')
                ->unique()
                ->constrained('internship_applications');

            $table->foreignId('department_id')->nullable()->change();
            $table->text('temporary_initial_password')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table): void {
            $table->dropForeign(['internship_application_id']);
            $table->dropUnique('interns_internship_application_id_unique');
            $table->dropColumn('internship_application_id');
            $table->foreignId('department_id')->nullable(false)->change();
            $table->dropColumn('temporary_initial_password');
        });
    }
};
