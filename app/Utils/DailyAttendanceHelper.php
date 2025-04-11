<?php

namespace App\Utils;

use App\Enums\TipeAbsensi;
use App\Models\Karyawan;

class DailyAttendanceHelper
{
    public static function getAttendanceData($startDate, $endDate = null)
    {
        $attendanceData = self::getQuery($startDate, $endDate)->get();

        return self::formatAttendanceData($attendanceData, !is_null($endDate));
    }

    private static function getQuery($startDate, $endDate = null)
    {
        return Karyawan::query()
            ->whereHas('absensi')
            ->with(['absensi' => function ($query) use ($startDate, $endDate) {
                if (!isset($endDate)) {
                    $query->where('tanggal', $startDate)
                        ->orderByRaw("FIELD(`absensis`.`jenisAbsen`, ?, ?, ?)", [
                            TipeAbsensi::AbsenMasuk->value,
                            TipeAbsensi::AbsenKeluar->value,
                            TipeAbsensi::Lembur->value
                        ]);
                } else {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->orderByRaw("FIELD(`absensis`.`jenisAbsen`, ?, ?, ?)", [
                            TipeAbsensi::AbsenMasuk->value,
                            TipeAbsensi::AbsenKeluar->value,
                            TipeAbsensi::Lembur->value
                        ]);;
                }
            }]);
    }

    private static function formatAttendanceData($attendanceData, $isMultiple)
    {
        if ($isMultiple) {
            return self::formatMultipleData($attendanceData);
        }
        return self::formatSingleAttendanceData($attendanceData);
    }

    private static function formatMultipleData($attendanceData) {
        $formattedData = [];
        $index = 1;

        foreach ($attendanceData as $karyawan) {
            $attendanceStats = [];
            $tempByDate = [];

            foreach ($karyawan->absensi as $record) {
                $dateKey = $record->tanggal->toDateString(); // use string key for clarity

                // Initialize if not exists
                if (!isset($tempByDate[$dateKey])) {
                    $tempByDate[$dateKey] = [
                        'tanggal' => $record->tanggal,
                        'jamMasuk' => null,
                        'jamKeluar' => null,
                        'clockInStatus' => null,
                        'clockOutStatus' => null,
                    ];
                }

                if ($record->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value) {
                    $tempByDate[$dateKey]['jamMasuk'] = $record->waktu;
                    $tempByDate[$dateKey]['clockInStatus'] = $record->status->value;
                } elseif ($record->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value) {
                    $tempByDate[$dateKey]['jamKeluar'] = $record->waktu;
                    $tempByDate[$dateKey]['clockOutStatus'] = $record->status->value;
                }
            }

            // Now compile the grouped data per date
            foreach ($tempByDate as $dateData) {
                $status = match (true) {
                    $dateData['clockInStatus'] === 'tepatWaktu' && $dateData['clockOutStatus'] === 'tepatWaktu' => 'Tepat Waktu',
                    $dateData['clockInStatus'] === 'terlambat' => 'Terlambat',
                    $dateData['clockOutStatus'] === 'lebihAwal' => 'Pulang Lebih Awal',
                    ($dateData['clockInStatus'] === null) && ($dateData['clockOutStatus'] === null) => 'Tidak Absen',
                    $dateData['clockInStatus'] === null => 'Tidak Absen Masuk',
                    $dateData['clockOutStatus'] === null => 'Tidak Absen Pulang',
                    default => 'Tidak Diketahui'
                };

                $attendanceStats[] = [
                    'tanggal' => $dateData['tanggal'],
                    'jamMasuk' => $dateData['jamMasuk'],
                    'jamKeluar' => $dateData['jamKeluar'],
                    'status' => $status
                ];
            }

            $formattedData[] = [
                'index' => $index++,
                'nama' => $karyawan->nama,
                'stat' => $attendanceStats
            ];
        }

        return $formattedData;
    }


    private static function formatSingleAttendanceData($attendanceData)
    {
        $formattedData = [];
        $index = 1;

        foreach ($attendanceData as $karyawan) {
            $attendanceStats = [
                'jamMasuk' => null,
                'jamKeluar' => null,
                'status' => null
            ];

            $clockInStatus = null;
            $clockOutStatus = null;

            foreach ($karyawan->absensi as $record) {
                if ($record->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value) {
                    $attendanceStats['jamMasuk'] = $record->waktu;
                    $clockInStatus = $record->status->value;
                } elseif ($record->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value) {
                    $attendanceStats['jamKeluar'] = $record->waktu;
                    $clockOutStatus = $record->status->value;
                }
            }

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
                'nama' => $karyawan->nama,
                'stat' => $attendanceStats
            ];
        }

        return $formattedData;
    }
}
