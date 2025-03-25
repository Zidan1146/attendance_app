<?php

namespace App\Exports;

use App\Utils\DateHelper;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiMonthlyExport implements WithMultipleSheets
{
    private int $startMonth;
    private int $endMonth;
    private int $year;
    private $workers;

    public function __construct(
        $startMonth,
        $endMonth,
        $year,
        $workers
    ) {
        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
        $this->year = $year;
        $this->workers = $workers;
    }

    public function sheets(): array {
        $sheets = [];
        for($i = $this->startMonth; $i <= $this->endMonth; $i++) {
            $dates = DateHelper::getMonthDays($this->year, $i);
            $workers = $this->filterData($i);
            $sheets[] = new MonthlyExport(
                $workers,
                $dates,
                $i,
                $this->year
            );
        }
        return $sheets;
    }

    private function filterData($month) {
        $filteredWorkers = [];

        foreach ($this->workers as $worker) {
            $filteredAbsensi = [];

            foreach ($worker['absensi'] as $month_date => $absensiData) {
                $internalMonth = explode('_', $month_date)[0];
                $date = explode('_', $month_date)[1];
                $dateObj = Carbon::createFromDate($this->year, $internalMonth, $date);

                if ($dateObj->month === $month) {
                    $filteredAbsensi[$date] = $absensiData;
                }
            }

            $filteredWorkers[] = [
                'id' => $worker['id'],
                'nama' => $worker['nama'],
                'jabatan' => $worker['jabatan'],
                'absensi' => $filteredAbsensi
            ];
        }

        return $filteredWorkers;
    }
}
