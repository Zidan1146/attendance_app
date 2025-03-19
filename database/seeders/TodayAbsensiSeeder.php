<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodayAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Karyawan::count() === 0) {
            Karyawan::factory(10)->create();
        }

        $karyawans = Karyawan::all();

        foreach ($karyawans as $karyawan) {
            $tanggal = Carbon::today()->format('Y-m-d');

            Absensi::factory()->create([
                'tanggal' => $tanggal,
                'karyawan_id' => $karyawan->id,
            ]);

            if (rand(1, 100) <= 90) {
                Absensi::factory()->absenKeluar()->create([
                    'tanggal' => $tanggal,
                    'karyawan_id' => $karyawan->id,
                ]);
            }
        }
    }
}
