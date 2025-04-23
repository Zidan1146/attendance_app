<?php

use App\Enums\RolePosition;
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
        Schema::table('karyawans', function(Blueprint $table) {
            $table->dropColumn('jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawans', function(Blueprint $table) {
            $allowedJabatan = array_map(
                fn($case) => $case->value,
                RolePosition::cases()
            );

            $table->enum('jabatan', $allowedJabatan);
        });
    }
};
