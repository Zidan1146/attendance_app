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
        try {
            Schema::table('karyawans', function(Blueprint $table) {
                $table->string('foto')->after('nama')->nullable();
                $table->enum('permission', ['superadmin', 'admin', 'user'])->after('password');
                $table->foreignId('jabatan_id')->after('password')->nullable()->constrained();
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawans', function(Blueprint $table) {
            $table->dropColumn(['foto', 'permission', 'jabatan_id']);
        });
    }
};
