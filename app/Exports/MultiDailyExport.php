<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiDailyExport implements WithMultipleSheets
{
    private $startDate;
    private $endDate;

    public function __construct(
        $startDate,
        $endDate
    ) {
        $this->startDate = Carbon::createFromDate($startDate);
        $this->endDate = Carbon::createFromDate($endDate);
    }
    public function sheets(): array {
        $sheets = [];
        $currentDate = $this->startDate->copy();
        while ($currentDate <= $this->endDate) {
            $sheets[] = new DailyExport($currentDate->copy()->format('Y-m-d H:i:s'));
            $currentDate->addDay();
        }

        return $sheets;
    }
}
