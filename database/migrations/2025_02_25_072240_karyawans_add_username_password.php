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
            Schema::table('karyawans', function (Blueprint $table) {
                $table->string('username')->after('jabatan');
                $table->string('password')->after('username');
            });
        } catch (\Throwable $th) {
            $this->down();
            throw $th;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawans', function(Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('password');
        });
    }
};
