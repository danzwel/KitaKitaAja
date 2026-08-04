<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            if (! Schema::hasColumn('interns', 'photo')) {
                $table->string('photo')->nullable();
            }

            if (! Schema::hasColumn('interns', 'email')) {
                $table->string('email')->nullable();
            }

            if (! Schema::hasColumn('interns', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (! Schema::hasColumn('interns', 'address')) {
                $table->text('address')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $columns = array_values(array_filter(
                ['photo', 'email', 'phone', 'address'],
                fn (string $column): bool => Schema::hasColumn('interns', $column),
            ));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
