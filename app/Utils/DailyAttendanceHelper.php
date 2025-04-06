<?php

namespace App\Utils;

use App\Enums\TipeAbsensi;
use App\Models\Karyawan;

class DailyAttendanceHelper
{
    public static function getAttendanceData($date)
    {
        $attendanceData = Karyawan::query()
            ->whereHas('absensi')
            ->with(['absensi' => function ($query) use ($date) {
                $query->where('tanggal', $date)
                    ->orderByRaw("FIELD(`absensis`.`jenisAbsen`, ?, ?, ?)", [
                        TipeAbsensi::AbsenMasuk->value,
                        TipeAbsensi::AbsenKeluar->value,
                        TipeAbsensi::Lembur->value
                    ]);
            }])
            ->get();

        return self::formatAttendanceData($attendanceData);
    }

    private static function formatAttendanceData($attendanceData)
    {
        $formattedData = [];
        $index = 1;

        foreach ($attendanceData as $employee) {
            $attendanceStats = [
                'jamMasuk' => null,
                'jamKeluar' => null,
                'status' => null
            ];

            $clockInStatus = null;
            $clockOutStatus = null;

            foreach ($employee->absensi as $record) {
                if ($record->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value) {
                    $attendanceStats['jamMasuk'] = $record->waktu;
                    $clockInStatus = $record->status->value;
                } elseif ($record->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value) {
                    $attendanceStats['jamKeluar'] = $record->waktu;
                    $clockOutStatus = $record->status->value;
                }
            }

            // Determine attendance status
            $attendanceStats['status'] = match (true) {
                $clockInStatus === 'tepatWaktu' && $clockOutStatus === 'tepatWaktu' => 'Tepat Waktu',
                $clockInStatus === 'terlambat' => 'Terlambat',
                $clockOutStatus === 'lebihAwal' => 'Pulang Lebih Awal',
                ($clockInStatus === null) && ($clockOutStatus === null) => 'Tidak Absen',
                $clockInStatus === null => 'Tidak Absen Masuk',
                $clockOutStatus === null => 'Tidak Absen Pulang',
                default => 'Tidak Diketahui'
            };

            $formattedData[] = [
                'index' => $index++,
                'nama' => $employee->nama,
                'todayStat' => $attendanceStats
            ];
        }

        return $formattedData;
    }
}
