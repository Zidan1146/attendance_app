<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Karyawan::count() === 0) {
            Karyawan::factory(10)->create(); // Ensure there are employees
        }

        $karyawans = Karyawan::all();

        foreach ($karyawans as $karyawan) {
            for ($i = 0; $i < rand(20, 26); $i++) { // Generate ~20-26 attendance days per employee
                $tanggal = Carbon::today()->subDays(rand(0, 30))->format('Y-m-d'); // Random past month date

                // Generate 'absenMasuk' first
                Absensi::factory()->create([
                    'tanggal' => $tanggal,
                    'karyawan_id' => $karyawan->id,
                ]);

                // 90% chance of having an 'absenKeluar' on the same day
                if (rand(1, 100) <= 90) {
                    Absensi::factory()->absenKeluar()->create([
                        'tanggal' => $tanggal,
                        'karyawan_id' => $karyawan->id,
                    ]);
                }
            }
        }
    }
}
