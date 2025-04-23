<?php

namespace App\Utils;

use App\Enums\TipeAbsensi;
use App\Models\Karyawan;
use Carbon\Carbon;

class MonthlyAttendanceHelper
{
    public static function getData(
        $role = null,
        $year,
        $startMonth,
        $endMonth = null,
        $paginateCount = null
    ) {
        $query = self::getAttendanceData(
            $role,
            $year,
            $startMonth,
            $endMonth,
        );

        $attendanceData = $paginateCount ? $query->paginate($paginateCount) : $query->get();
        if($paginateCount) {
            return [
                'resultData' => [...self::formatAttendanceData($attendanceData)],
                'rawData' => $attendanceData
            ];
        }

        return self::formatAttendanceData($attendanceData);
    }

    private static function getAttendanceData(
        $role = null,
        $year,
        $startMonth,
        $endMonth = null
    )
    {
        $workersQuery = Karyawan::query();

        if($role) {
            $workersQuery->where('jabatan_id', '=', $role);
        }

        $attendanceData = $workersQuery->whereHas('absensi', function ($query) use ($year, $startMonth, $endMonth) {
                $absensiQuery = $query->whereYear('tanggal', '=', $year);
                if(empty($endMonth)) {
                    $absensiQuery->whereMonth('tanggal', '=', $startMonth);
                }
                else {
                    $startDate = Carbon::createFromFormat('Y-n', "{$year}-{$startMonth}")->startOfMonth();
                    $endDate = Carbon::createFromFormat('Y-n', "{$year}-{$endMonth}")->endOfMonth();
                    $absensiQuery->whereBetween('tanggal', [$startDate, $endDate]);
                }
            })->with('absensi', function($query) use ($year, $startMonth, $endMonth) {
                if(empty($endMonth)) {
                    $query->whereMonth('tanggal', '=', $startMonth);
                }
                else {
                    $startDate = Carbon::createFromFormat('Y-n', "{$year}-{$startMonth}")->startOfMonth();
                    $endDate = Carbon::createFromFormat('Y-n', "{$year}-{$endMonth}")->endOfMonth();
                    $query->whereBetween('tanggal', [$startDate, $endDate]);
                }

                $query->orderBy('tanggal');
            });

        return $attendanceData;
    }

    private static function formatAttendanceData($attendanceData)
    {
        $result = [];
        foreach ($attendanceData as $worker) {
            if (!isset($result[$worker->id])) {
                $result[$worker->id] = [
                    'id' => $worker->id,
                    'nama' => $worker->nama,
                    'jabatan' => $worker->jabatan->nama ?? 'Tanpa Jabatan',
                    'absensi' => []
                ];
            }

            foreach ($worker->absensi as $absensi) {
                $date = $absensi->tanggal->format('j');
                $month = $absensi->tanggal->format('m');

                if (!isset($result[$worker->id]['absensi']["{$month}_{$date}"])) {
                    $result[$worker->id]['absensi']["{$month}_{$date}"] = [
                        'tanggal' => $date,
                        'absen_masuk_status' => null,
                        'absen_keluar_status' => null,
                    ];
                }

                if ($absensi->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value) {
                    $result[$worker->id]['absensi']["{$month}_{$date}"]['absen_masuk_status'] = $absensi->status->value;
                } elseif ($absensi->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value) {
                    $result[$worker->id]['absensi']["{$month}_{$date}"]['absen_keluar_status'] = $absensi->status->value;
                }
            }
        }
        return $result;
    }
}
