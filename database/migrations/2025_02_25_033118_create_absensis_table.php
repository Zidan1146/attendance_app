<?php

use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
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
            Schema::create('absensis', function (Blueprint $table) {
                $allowedAttendanceType = array_map(
                    fn($case) => $case->value,
                    AttendanceType::cases()
                );

                $allowedStatus = array_map(
                    fn($case) => $case->value,
                    AttendanceStatus::cases()
                );

                $table->id();

                $table->date('tanggal');
                $table->time('waktu');
                $table->enum('jenisAbsen', $allowedAttendanceType);
                $table->enum('status', $allowedStatus);
                $table->text('deskripsi')->nullable();

                $table->foreignId('karyawan_id')->constrained();
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
        Schema::dropIfExists('absensis');
    }
};
