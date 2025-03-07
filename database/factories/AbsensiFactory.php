<?php

namespace Database\Factories;

use App\Enums\StatusAbsen;
use App\Enums\TipeAbsensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggal = Carbon::today()->subDays(rand(0, 30))->format('Y-m-d'); // Random date in the past month
        $karyawan = Karyawan::inRandomOrder()->first()->id ?? Karyawan::factory();

        // Define controlled check-in times (07:30 to 10:00 for lateness check)
        $masukTime = Carbon::createFromTime(7, 30)->addMinutes(rand(0, 150));

        // Determine if the employee is late or on time
        $statusMasuk = $masukTime->format('H:i') > '09:30' ? 'terlambat' : 'tepatWaktu';

        return [
            'tanggal' => $tanggal,
            'waktu' => $masukTime->format('H:i:s'),
            'jenisAbsen' => TipeAbsensi::AbsenMasuk,
            'status' => $statusMasuk,
            'deskripsi' => $this->faker->optional(0.2)->sentence(),
            'karyawan_id' => $karyawan,
        ];
    }

    public function absenKeluar() {
        return $this->state(function($attributes) {
            $keluarTime = Carbon::createFromTime(16, 0)->addMinutes(rand(0, 120));

            return [
                'waktu' => $keluarTime->format('H:i:s'),
                'jenisAbsen' => TipeAbsensi::AbsenKeluar,
                'status' => ((rand(1, 100) <= 30) ? StatusAbsen::LebihAwal : StatusAbsen::TepatWaktu),
            ];
        });
    }
}
