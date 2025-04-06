<?php

namespace App\Livewire\Pages;

use App\Enums\ExportErrorType;
use App\Enums\RolePosition;
use App\Enums\TipeAbsensi;
use App\Exports\MultiDailyExport;
use App\Exports\MultiMonthlyExport;
use App\Models\Absensi;
use App\Utils\DateHelper;
use App\Models\Karyawan;
use App\Utils\MonthlyAttendanceHelper;
use Carbon\Carbon;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Report extends BasePage
{
    use WithPagination;

    public $days;
    public $selectedMonth;
    public $currentMonthName;
    public $months;
    public $years;
    public $selectedYear;
    public $roles;
    public $selectedRole;
    public $now;
    public $searchTerm;
    public $reportPeriodType;
    public $selectedExportRole;
    public $selectedExportYear;
    public $selectedExportStartMonth;
    public $selectedExportEndMonth;
    public $availableDates;
    public $selectedStartDate;
    public $selectedEndDate;
    public $dateDiff;
    public $exportErrorType;
    public $reportFileType;

    public function mount() {
        parent::authCheck();
        $this->months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => Carbon::createFromDate(null, $month, 1)->translatedFormat('F')];
        });

        $this->years = Absensi::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $now = Carbon::now();
        $this->days = DateHelper::getMonthDays($now->year, $now->month);

        $this->selectedRole = '';

        $this->currentMonthName = DateHelper::getMonthName($now->month);
        $this->selectedMonth = $now->month;
        $this->selectedYear = $now->year;

        $this->roles = RolePosition::cases();
        $this->now = $now;

        $this->reportPeriodType = "monthly";
        $this->selectedExportYear = $now->year;
        $this->selectedExportStartMonth = $now->month;
        $this->selectedExportEndMonth = $now->month;

        $this->selectedStartDate = '';
        $this->selectedEndDate = '';

        $this->availableDates = Absensi::distinct()->orderBy('tanggal')->pluck('tanggal');
        $this->exportErrorType = 0;

        $this->reportFileType = 'xlsx';
    }

    public function calculateDateDiff()
    {
        if ($this->selectedStartDate && $this->selectedEndDate) {
            $start = Carbon::parse($this->selectedStartDate);
            $end = Carbon::parse($this->selectedEndDate);
            $this->dateDiff = $start->diffInDays($end);
        } else {
            $this->dateDiff = 0;
        }
    }

    public function updatingSelectedMonth($value) {
        $this->days = DateHelper::getMonthDays(null, $value);
        $this->currentMonthName = DateHelper::getMonthName($value);
    }

    public function exportMonthlyData() {
        $workers = MonthlyAttendanceHelper::getData(
            $this->selectedExportRole,
            $this->selectedExportYear,
            $this->selectedExportStartMonth,
            $this->selectedExportEndMonth
        );
        $startMonthName = DateHelper::getMonthName($this->selectedExportStartMonth);
        $endMonthName = DateHelper::getMonthName($this->selectedExportEndMonth);

        return Excel::download(new MultiMonthlyExport(
                $this->selectedExportStartMonth,
                $this->selectedExportEndMonth,
                $this->selectedExportYear,
                $workers
            ),
            "absensi_{$startMonthName}_{$endMonthName}_{$this->now}.xlsx"
        );
    }


    public function exportDailyData() {
        return Excel::download(
            new MultiDailyExport(
                $this->selectedStartDate,
                $this->selectedEndDate
            ),
            "absensi_{$this->selectedStartDate}_{$this->selectedEndDate}.xlsx"
        );
    }
    public function export() {
        if($this->reportFileType === 'pdf') {
            if($this->reportPeriodType === 'monthly') {
                $workers = MonthlyAttendanceHelper::getData(
                    null,
                    $this->selectedExportYear,
                    $this->selectedMonth
                );
                $requestData = [
                    'days' => $this->days,
                    'workers' => $workers,
                    'now' => $this->now->toDateTimeString(),
                    'year' => $this->selectedExportYear,
                    'startMonth' => $this->selectedExportStartMonth,
                    'endMonth' => $this->selectedExportEndMonth
                ];
                $this->redirectRoute('pdf.download.monthly', $requestData);
            }
            else if($this->reportPeriodType === 'daily') {
                $requestData = [
                    'startDate' => $this->selectedStartDate,
                    'endDate' => $this->selectedEndDate
                ];
                $this->redirectRoute('pdf.download.daily', $requestData);
            }
        }

        else if($this->reportFileType === 'xlsx') {
            if($this->reportPeriodType === 'monthly') {
                return $this->exportMonthlyData();
            }
            if($this->reportPeriodType === 'daily') {
                return $this->exportDailyData();
            }
        }
    }

    public function updated($property)
    {
        if(in_array($property, ['selectedStartDate', 'selectedEndDate', 'selectedYear', 'selectedExportStartMonth', 'selectedExportEndMonth', 'reportPeriodType', 'reportFileType'])) {
            $this->resetPage();
            $this->calculateDateDiff();
            $this->exportErrorType = $this->validateExport();
        }
    }

    public function validateExport() {
        $isDataDaily = $this->reportPeriodType === 'daily';
        $isDateSelectionEmpty = $this->selectedStartDate === '' || $this->selectedEndDate === '';
        $isMonthDifference = $this->dateDiff > 30;
        $isInvalidDateDiff = $this->dateDiff < 0;

        if($isInvalidDateDiff && $isDataDaily) {
            return ExportErrorType::InvalidSelection->value;
        }

        if($isDateSelectionEmpty && $isDataDaily) {
            return ExportErrorType::EmptySelection->value;
        }

        if($isMonthDifference && $isDataDaily) {
            return ExportErrorType::ExceedLimit->value;
        }

        $isDataMonthly = $this->reportPeriodType === 'monthly';
        $monthDifference = $isDataMonthly ? $this->selectedExportEndMonth - $this->selectedExportStartMonth : 0;
        if($monthDifference < 0) {
            return ExportErrorType::InvalidSelection->value;
        }

        if($monthDifference > 2) {
            return ExportErrorType::ExceedLimit->value;
        }
        return ExportErrorType::None->value;
    }

    public function render()
    {
        $workers = MonthlyAttendanceHelper::getData(
            $this->selectedRole,
            $this->selectedYear,
            $this->selectedMonth,
            null,
            5
        );

        $finalWorkers = [];
        foreach ($workers['resultData'] as $worker) {
            $worker['absensi'] = array_values($worker['absensi']);
            $finalWorkers[] = $worker;
        }

        $perPage = $workers['rawData']->perPage();
        $currentPage = $workers['rawData']->currentPage();
        $startNumber = ($currentPage - 1) * $perPage;

        $workers = new \Illuminate\Pagination\LengthAwarePaginator(
            array_values($finalWorkers),
            $workers['rawData']->total(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );


        return view(
            'livewire.pages.report',
            [
                'workers' => $workers,
                'startNumber' => $startNumber
            ]
        );
    }
}
