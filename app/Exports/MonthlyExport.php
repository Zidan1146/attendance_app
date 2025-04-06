<?php

namespace App\Exports;

use App\Utils\DateHelper;
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

class MonthlyExport implements FromCollection, ShouldAutoSize, WithHeadings, WithStyles, WithTitle, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $workers;
    protected $days;
    protected $month;
    protected $year;

    public function __construct($workers, $days, $month, $year)
    {
        $this->workers = $workers;
        $this->days = $days;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $data = [];

        $now = Carbon::now();

        $index = 0;
        foreach ($this->workers as $worker) {
            $row = [
                $index + 1,
                $worker['nama'],
                $worker['jabatan']->name
            ];
            $index++;

            foreach ($this->days as $day) {
                $attendance = collect($worker['absensi'])->firstWhere('tanggal', $day['day_number']);

                $isWeekend = $day['is_weekend'];
                $isPast = ((int) $now->format('j') < $day['day_number']) && ((int) $now->month <= $this->month);
                $status = !$isWeekend && !$isPast ? 'A' : '';

                if ($attendance) {
                    $isClockInOnTime = $attendance['absen_masuk_status'] === 'tepatWaktu';
                    $isClockOutOnTime = $attendance['absen_keluar_status'] === 'tepatWaktu';

                    $isClockInLate = $attendance['absen_masuk_status'] === 'terlambat';
                    $isLeaveEarly = $attendance['absen_keluar_status'] === 'lebihAwal';

                    $noClockIn = !isset($attendance['absen_masuk_status']);
                    $noClockOut = !isset($attendance['absen_keluar_status']);

                    $status = match (true) {
                        $isWeekend => '',
                        $noClockOut => 'TP',
                        $noClockIn => 'TM',
                        $isClockInLate => 'TL',
                        $isLeaveEarly => 'PW',
                        ($isClockInOnTime && $isClockOutOnTime) => 'V',
                        default => 'A'
                    };
                }

                $row[] = $status;
            }
            $row[] = '';
            $rowNumber = $index + 1;
            $rowCount = Coordinate::stringFromColumnIndex(count($this->days) + 4);

            $row[] = "=COUNTIF(D$rowNumber:$rowCount$rowNumber, \"V\")";
            $row[] = "=COUNTIF(D$rowNumber:$rowCount$rowNumber, \"TP\")";
            $row[] = "=COUNTIF(D$rowNumber:$rowCount$rowNumber, \"TM\")";
            $row[] = "=COUNTIF(D$rowNumber:$rowCount$rowNumber, \"TL\")";
            $row[] = "=COUNTIF(D$rowNumber:$rowCount$rowNumber, \"PW\")";
            $row[] = "=COUNTIF(D$rowNumber:$rowCount$rowNumber, \"A\")";

            $data[] = $row;
        }
        $data[] = [''];
        $data[] = ['Keterangan'];
        $data[] = ['V', 'Tepat waktu'];
        $data[] = ['TL', 'Terlambat masuk'];
        $data[] = ['PW', 'Pulang sebelum waktunya'];
        $data[] = ['TM', 'Tidak absen masuk'];
        $data[] = ['TP', 'Tidak absen pulang'];
        $data[] = ['A', 'Tidak absen'];

        return collect($data);
    }

    public function headings(): array
    {
        $headerRow = ['No', 'Nama', 'Jabatan'];
        foreach ($this->days as $day) {
            $headerRow[] = $day['day_number'];
        }

        $headerRow[] = '';

        $statsHeader = ['V', 'TP', 'TM', 'TL', 'PW', 'A'];
        $headerRows = [...$headerRow, ...$statsHeader];

        return $headerRows;
    }

    public function styles(Worksheet $sheet)
    {
        $dateEndRow = count($this->days) + 3;
        $rowChar = Coordinate::stringFromColumnIndex($dateEndRow);
        $sheet->getStyle("A1:{$rowChar}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => 'center'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFEEEEEE']],
        ]);

        $sheet->getStyle("D2:$rowChar$dateEndRow")->applyFromArray([
            'alignment' => ['horizontal' => 'center'],
        ]);

        $workersCount = count($this->workers) + 1;
        $sheet->getStyle("A1:$rowChar$workersCount")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);

        $statRowStartNumber = $dateEndRow + 2;
        $statRowStartChar = Coordinate::stringFromColumnIndex($statRowStartNumber);

        $statRowEndNumber = $statRowStartNumber + 5;
        $statRowEndChar = Coordinate::stringFromColumnIndex($statRowEndNumber);

        $sheet->getStyle("{$statRowStartChar}1:{$statRowEndChar}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => 'center'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFEEEEEE']],
        ]);

        $sheet->getStyle("{$statRowStartChar}1:$statRowEndChar$workersCount")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'alignment' => ['horizontal' => 'center'],
        ]);

        $informationHeaderColumn = count($this->workers) + 3;
        $informationTableEndColumn = $informationHeaderColumn + 6;
        $informationHeaderCell = "A$informationHeaderColumn:B$informationHeaderColumn";
        $informationTableCells = "A$informationHeaderColumn:B$informationTableEndColumn";

        $this->styleAttendanceTable($sheet, $informationTableCells, 1, $informationHeaderColumn);

        $sheet->getStyle($informationHeaderCell)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => 'center'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFEEEEEE']],
        ]);

        $sheet->getStyle($informationTableCells)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);

        return [];
    }

    public function title(): string {
        $monthName = DateHelper::getMonthName($this->month);
        return "{$monthName} {$this->year}";
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $informationHeaderColumn = count($this->workers) + 3;
                $informationHeaderCells = "A$informationHeaderColumn:B$informationHeaderColumn";
                $sheet->mergeCells($informationHeaderCells);

                $attendanceRecordStart = "D2";

                $workersEndColumn = count($this->workers) + 1;
                $workersRecordEndLetter = Coordinate::stringFromColumnIndex(count($this->days) + 3);

                $attendanceRecordEnd = "{$workersRecordEndLetter}{$workersEndColumn}";
                $attendanceRecordRange = "{$attendanceRecordStart}:{$attendanceRecordEnd}";

                $this->styleAttendanceTable($sheet, $attendanceRecordRange);
            }
        ];
    }

    private function styleAttendanceTable(Worksheet $sheet, string $recordRange, int $colGap = 4, int $rowGap = 2) {
        foreach ($sheet->rangeToArray($recordRange) as $rowIndex => $row) {
            foreach ($row as $colIndex => $cellValue) {
                $columnLetter = Coordinate::stringFromColumnIndex($colIndex + $colGap);
                $rowNumber = $rowIndex + $rowGap;
                $cellCoordinate = "{$columnLetter}{$rowNumber}";

                $styleArray = match($cellValue) {
                    'A' => [
                        'font' => ['color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DC2626']], // red bg
                    ],
                    'V' => [
                        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '16A34A']], // green bg
                        'font' => ['color' => ['rgb' => 'FFFFFF']],
                    ],
                    'TL', 'TP', 'PW', 'TM' => [
                        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FACC15']], // yellow bg
                    ],
                    default => [],
                };

                $sheet->getStyle($cellCoordinate)->applyFromArray($styleArray);
            }
        }
    }
}
