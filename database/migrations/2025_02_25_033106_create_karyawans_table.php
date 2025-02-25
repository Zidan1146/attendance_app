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
        try {
            Schema::create('karyawans', function (Blueprint $table) {
                $allowedJabatan = array_map(
                    fn($case) => $case->value,
                    RolePosition::cases()
                );

                $table->id();

                $table->string('nama');
                $table->string('alamat');
                $table->string('noTelepon');
                $table->enum('jabatan', $allowedJabatan);

                $table->timestamps();
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
        Schema::dropIfExists('karyawans');
    }
};
