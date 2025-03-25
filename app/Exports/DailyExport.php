<?php

namespace App\Exports;

use App\Enums\TipeAbsensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $date;
    public function __construct(
        $date
    ) {
        $this->date = $date;
    }

    public function title(): string {
        $title = Carbon::createFromDate($this->date)->translatedFormat('j F Y');
        return $title;
    }

    public function collection()
    {
        $todayData = Karyawan::query()
        ->whereHas('absensi')
        ->with('absensi', function ($query) {
            $attendanceTypeOrder = [TipeAbsensi::AbsenMasuk->value, TipeAbsensi::AbsenKeluar->value, TipeAbsensi::Lembur->value];
            $query->where('tanggal', "=", $this->date)
                ->orderByRaw("FIELD(`absensis`.`jenisAbsen`,'".implode("','", $attendanceTypeOrder)."')")
                ->get();
        })
        ->get();

        $index = 0;
        $aggregatedData = [];
        foreach ($todayData as $data) {
            if (!isset($aggregatedData[$data->id])) {
                $aggregatedData[$data->id] = [
                    "index" => $index + 1,
                    "nama" => $data->nama,
                    "todayStat" => [
                        'jamMasuk' => null,
                        'jamKeluar' => null,
                        'status' => null
                    ]
                ];
            }

            $clockInStatus = null;
            $clockOutStatus = null;

            foreach ($data->absensi as $todayAttendance) {
                if ($todayAttendance->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value) {
                    $aggregatedData[$data->id]['todayStat']['jamMasuk'] = $todayAttendance->waktu;
                    $clockInStatus = $todayAttendance->status->value;
                } elseif ($todayAttendance->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value) {
                    $aggregatedData[$data->id]['todayStat']['jamKeluar'] = $todayAttendance->waktu;
                    $clockOutStatus = $todayAttendance->status->value;
                }
            }

            // Apply logic AFTER both absensi processed:
            $aggregatedData[$data->id]['todayStat']['status'] = match (true) {
                $clockInStatus === 'tepatWaktu' && $clockOutStatus === 'tepatWaktu' => 'Tepat Waktu',
                $clockInStatus === 'terlambat' => 'Terlambat',
                $clockOutStatus === 'lebihAwal' => 'Pulang Lebih Awal',
                ($clockInStatus === null) && ($clockOutStatus === null) => 'Tidak Absen',
                $clockInStatus === null => 'Tidak Absen Masuk',
                $clockOutStatus === null => 'Tidak Absen Pulang',
                default => 'Tidak Diketahui'
            };

            $index++;
        }

        $collectionData = [];
        foreach($aggregatedData as $karyawan) {
            $row = [
                $karyawan['index'],
                $karyawan['nama']
            ];

            foreach($karyawan['todayStat'] as $statKey => $statValue) {
                $row[]  = $statValue ?: '-';
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
