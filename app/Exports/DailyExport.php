<?php

namespace App\Exports;

use App\Enums\TipeAbsensi;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $todayData = Karyawan::query()
        ->whereHas('absensi', function($query) {
            $today = Carbon::today();

            $attendanceTypeOrder = [TipeAbsensi::AbsenMasuk, TipeAbsensi::AbsenKeluar, TipeAbsensi::Lembur];
            $query->where('tanggal', '=', $today)
                ->orderByRaw("FIELD('".implode("','", $attendanceTypeOrder)."')")
                ->get();
            }
        )->get();

        $index = 0;
        $aggregatedData = [];
        foreach($todayData as $data) {
            $aggregatedData = [
                "index" => $index + 1,
                "nama" => $data->nama
            ];

            foreach($data->absensi as $todayAttendance) {
                if(!isset($aggregatedData[$data->id]['todayStat'])) {
                    $aggregatedData[$data->id]['todayStat'] = [
                        'jamMasuk' => null,
                        'jamKeluar' => null,
                        'status' => null
                    ];
                }

                if ($todayAttendance->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value) {
                    $aggregatedData[$data->id]['todayStat']['jamMasuk'] = $todayAttendance->waktu;
                } elseif ($todayAttendance->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value) {
                    $aggregatedData[$data->id]['todayStat']['jamKeluar'] = $todayAttendance->waktu;
                }

                if(!isset($aggregatedData[$data->id]['todayStat']['status'])) {
                    $aggregatedData[$data->id]['todayStat']['status'] = $todayAttendance->status->value;
                } elseif(isset($aggregatedData[$data->id]['todayStat']['status'])) {
                    $storedStatus = $aggregatedData[$data->id]['todayStat']['status'];
                    $currentStatus = $todayAttendance->status->value;

                    $isClockInOnTime = $storedStatus === 'tepatWaktu';
                    $isClockOutOnTime = $currentStatus === 'tepatWaktu';
                    $isOnTime = $isClockInOnTime && $isClockOutOnTime;

                    $isClockInLate = $storedStatus === 'terlambat';
                    $isLeaveEarly = $currentStatus === 'lebihAwal';

                    $noClockIn = !isset($storedStatus);
                    $noClockOut = !isset($currentStatus);

                    $aggregatedData[$data->id]['todayStat']['status'] = match(true) {
                        $isOnTime => 'Tepat Waktu',
                        $isClockInLate => 'Terlambat',
                        $isLeaveEarly => 'Pulang Lebih Awal',
                        $noClockIn => 'Tidak Absen Masuk',
                        $noClockOut => 'Tidak Absen Pulang'
                    };
                }
            }
        }

        $collectionData = [];
        $index = 0;
        foreach($aggregatedData as $karyawan) {
            $row = [
                $index + 1,
                $karyawan->nama
            ];

            foreach($karyawan['todayStat'] as $statKey => $statValue) {
                $row[]  = $statValue;
            }
            $collectionData[] = $row;
        }

        return collect($collectionData);
    }

    public function headings(): array {
        return [
            'No',
            'Nama',
            'Jam Masuk',
            'Jam Keluar',
            'Status'
        ];
    }
}
