<?php

namespace App\Exports;

use App\Utils\DailyAttendanceHelper;
use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles, WithEvents
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
        $aggregatedData = DailyAttendanceHelper::getAttendanceData($this->date);

        $collectionData = [];
        foreach($aggregatedData as $karyawan) {
            $row = [
                $karyawan['index'],
                $karyawan['nama']
            ];

            foreach($karyawan['todayStat'] as $_ => $statValue) {
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

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => 'center'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFEEEEEE']]
        ]);

        $workerData = Karyawan::with('absensi')->get();
        $workersCount = count($workerData);
        $tableHeight = $workersCount + 1;

        $sheet->getStyle("A1:E{$tableHeight}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ]
        ]);

        $sheet->getStyle("A2:E{$tableHeight}")->applyFromArray([
            'alignment' => ['horizontal' => 'left'],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $workerData = Karyawan::with('absensi')->get();
                $workersCount = count($workerData);
                $tableHeight = $workersCount + 1;

                $range = "A1:E{$tableHeight}";
                foreach ($sheet->rangeToArray($range) as $rowIndex => $row) {
                    foreach ($row as $colIndex => $cellValue) {
                        $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                        $rowNumber = $rowIndex + 1;
                        $cellCoordinate = "{$columnLetter}{$rowNumber}";

                        $styleArray = match($cellValue) {
                            'Tidak Absen' => [
                                'font' => ['color' => ['rgb' => 'FFFFFF']],
                                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DC2626']], // red bg
                            ],
                            'Tepat Waktu' => [
                                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '16A34A']], // green bg
                                'font' => ['color' => ['rgb' => 'FFFFFF']],
                            ],
                            'Pulang Lebih Awal', 'Tidak Absen Masuk', 'Tidak Absen Pulang', 'Terlambat' => [
                                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FACC15']], // yellow bg
                            ],
                            default => [],
                        };

                        $sheet->getStyle($cellCoordinate)->applyFromArray($styleArray);
                    }
                }
            }
        ];
    }
}
