<?php

namespace App\Livewire\Pages;

use App\Enums\RolePosition;
use App\Enums\TipeAbsensi;
use App\Exports\KaryawanReportExport;
use App\Models\Absensi;
use App\Utils\DateHelper;
use App\Models\Karyawan;
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
    public $selectedRole = '';
    public $now;
    public $searchTerm;
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

        $this->currentMonthName = DateHelper::getMonthName($now->month);
        $this->selectedMonth = $now->month;
        $this->selectedYear = $now->year;

        $this->roles = RolePosition::cases();
        $this->now = $now;
    }

    public function updatingSelectedMonth($value) {
        $this->days = DateHelper::getMonthDays(null, $value);
        $this->currentMonthName = DateHelper::getMonthName($value);
    }

    public function exportXls() {
        $workersData = $this->getWorkers()->get();
        $workers = $this->aggregateData($workersData);

        return Excel::download(new KaryawanReportExport(
                $workers,
                $this->days,
                $this->selectedMonth,
                $this->selectedYear
            ),
            "absensi_{$this->now}.xlsx"
        );
    }

    public function updated($property)
    {
        if(
            $property === 'selectedRole' ||
            $property === 'selectedMonth' ||
            $property === 'selectedYear'
        ) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $workers = $this->getWorkers()->paginate(5);

        $perPage = $workers->perPage();
        $currentPage = $workers->currentPage();
        $startNumber = ($currentPage - 1) * $perPage;

        $formattedWorkers = $this->aggregateData($workers);

        $finalWorkers = [];
        foreach ($formattedWorkers as $worker) {
            $worker['absensi'] = array_values($worker['absensi']);
            $finalWorkers[] = $worker;
        }

        // Convert it back to a paginated structure
        $workers = new \Illuminate\Pagination\LengthAwarePaginator(
            array_values($finalWorkers),
            $workers->total(),
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

    private function getWorkers() {
        $workersQuery = Karyawan::query();

        if($this->selectedRole) {
            $workersQuery->where('jabatan', '=', $this->selectedRole);
        }
        if($this->searchTerm) {
            $workersQuery->where('nama', 'like', "%{$this->searchTerm}%");;
        }

        $workers = $workersQuery->whereHas('absensi', function ($query) {
            $query->whereYear('tanggal', '=', $this->selectedYear)
                ->whereMonth('tanggal', '=', $this->selectedMonth);
            })->with('absensi', function($query) {
                $query->whereMonth('tanggal', $this->selectedMonth)
                    ->orderBy('tanggal');
            });

        return $workers;
    }

    private function aggregateData($workers) {
        $formattedWorkers = [];
        foreach ($workers as $worker) {
            if (!isset($formattedWorkers[$worker->id])) {
                $formattedWorkers[$worker->id] = [
                    'id' => $worker->id,
                    'nama' => $worker->nama,
                    'jabatan' => $worker->jabatan
                ];
            }

            foreach ($worker->absensi as $absensi) {
                $date = $absensi->tanggal->format('j');

                if (!isset($formattedWorkers[$worker->id]['absensi'][$date])) {
                    $formattedWorkers[$worker->id]['absensi'][$date] = [
                        'tanggal' => $date,
                        'absen_masuk_status' => null,
                        'absen_keluar_status' => null,
                    ];
                }

                if ($absensi->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value) {
                    $formattedWorkers[$worker->id]['absensi'][$date]['absen_masuk_status'] = $absensi->status->value;
                } elseif ($absensi->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value) {
                    $formattedWorkers[$worker->id]['absensi'][$date]['absen_keluar_status'] = $absensi->status->value;
                }
            }
        }

        return $formattedWorkers;
    }
}
