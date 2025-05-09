<?php

namespace App\Livewire\Pages;

use App\Enums\StatusAbsen;
use App\Enums\TipeAbsensi;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Livewire\Attributes\On;

class Dashboard extends BasePage
{
    public $userCount;
    public $userClockInCount;
    public $userClockOutCount;
    public $clockInCount;
    public $clockOutCount;
    public $earlyClockOut;
    public $lateCount;
    public $absentCount;
    public $today;
    public $searchTerm;
    public $jenisAbsenEnum;
    public $statusAbsenEnum;
    public $attendanceData;
    public $categories;
    public $years;
    public $selectedYear;
    public $isUserAnAdmin;

    public function mount() {
        parent::userInit();
        $this->years = Absensi::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $now = Carbon::now();

        $this->selectedYear = $now->year;
        $this->jenisAbsenEnum = TipeAbsensi::class;
        $this->statusAbsenEnum = StatusAbsen::class;
        $this->today = Carbon::today();
        $this->isUserAnAdmin = $this->user->permission->value !== 'user';
        $this->setStatistics();

        $this->categories = collect($this->attendanceData)
                ->flatMap(function ($monthData) {
                    return array_keys($monthData);
                })
                ->unique()
                ->values()
                ->toArray();
    }

    private function permissionWrapper(callable $adminFunction, callable $userFunction) {
        if($this->user->permission->value !== 'user') {
            $adminFunction();
            return;
        }
        $userFunction();
    }

    private function setStatistics() {
        $this->permissionWrapper(
            fn() => $this->setAllStatisticts(),
            fn() => $this->setSelfStatisticts()
        );
    }

    private function setSelfStatisticts() {
        $this->userClockInCount = $this->user->absensi
            ->filter(function($absensi) {
                return
                    $absensi->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value;
            })->count();
        $this->userClockOutCount = $this->clockOutCount = $this->user->absensi
            ->filter(function($absensi) {
                return
                    $absensi->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value;
            })->count();
        $this->clockInCount = $this->user->absensi
            ->filter(function($absensi) {
                return
                    $absensi->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value &&
                    $absensi->status->value === StatusAbsen::TepatWaktu->value;
            })->count();
        $this->clockOutCount = $this->user->absensi
            ->filter(function($absensi) {
                return
                    $absensi->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value &&
                    $absensi->status->value === StatusAbsen::TepatWaktu->value;
            })->count();

        $this->lateCount = $this->user->absensi
            ->filter(function($absensi) {
                return
                    $absensi->jenisAbsen->value === TipeAbsensi::AbsenMasuk->value &&
                    $absensi->status->value === StatusAbsen::Terlambat->value;
            })->count();

        $this->earlyClockOut = $this->user->absensi
            ->filter(function($absensi) {
                return
                    $absensi->jenisAbsen->value === TipeAbsensi::AbsenKeluar->value &&
                    $absensi->status->value === StatusAbsen::LebihAwal->value;
            })->count();
        $this->absentCount = 0;

        $this->loadAttendanceData();
    }

    private function setAllStatisticts() {
        $this->userCount = Karyawan::count();
        $this->clockInCount = Karyawan::whereHas('absensi', function($query) {
            $query->whereDate('tanggal', $this->today)
                ->where('jenisAbsen', '=', TipeAbsensi::AbsenMasuk->value)
                ->where('status', '=', StatusAbsen::TepatWaktu->value);
        })->count();
        $this->clockOutCount = Karyawan::whereHas('absensi', function($query) {
            $query->whereDate('tanggal', $this->today)
                ->where('jenisAbsen', '=', TipeAbsensi::AbsenKeluar->value)
                ->where('status', '=', StatusAbsen::TepatWaktu->value);
        })->count();

        $this->lateCount = Karyawan::whereHas('absensi', function($query) {
            $query->whereDate('tanggal', $this->today)
                ->where('jenisAbsen', '=', TipeAbsensi::AbsenMasuk->value)
                ->where('status', '=', StatusAbsen::Terlambat->value);
        })->count();
        $this->earlyClockOut = Karyawan::whereHas('absensi', function($query) {
            $query->whereDate('tanggal', $this->today)
                ->where('jenisAbsen', '=', TipeAbsensi::AbsenKeluar->value)
                ->where('status', '=', StatusAbsen::LebihAwal->value);
        })->count();
        $this->absentCount = Karyawan::whereDoesntHave('absensi', function($query) {
            $query->whereDate('tanggal', $this->today);
        })->count();

        $this->loadAttendanceData();
    }

    public function updatedSelectedYear() {
        $this->loadAttendanceData();
    }

    #[On('loadChartData')]
    public function childLoadChartData($value) {
        $this->selectedYear = $value;
        $this->loadAllAttendanceData();
    }

    public function loadAttendanceData() {
        $this->permissionWrapper(
            fn() => $this->loadAllAttendanceData(),
            fn() => $this->loadSelfAttendanceData()
        );
    }

    private function loadAllAttendanceData() {
        $data = Absensi::whereYear('tanggal', '=', $this->selectedYear)
            ->get()
            ->groupBy(fn ($a) => Carbon::parse($a->tanggal)->format('F'))
            ->map(function ($records) {
                return $records->groupBy(function ($record) {
                    return $record->jenisAbsen->value . ' - ' . $record->status->value;
                })->map->count();
            });

        $this->attendanceData = $data->toArray();

        $this->dispatch('attendanceUpdated', $this->attendanceData);
    }

    private function loadSelfAttendanceData() {
        $data = $this->user->absensi()
                ->whereYear('tanggal', '=', $this->selectedYear)
                ->get()
                ->groupBy(fn ($a) => Carbon::parse($a->tanggal)->format('F'))
                ->map(function ($records) {
                    return $records->groupBy(function ($record) {
                        return $record->jenisAbsen->value . ' - ' . $record->status->value;
                    })->map->count();
                });

        $this->attendanceData = $data->toArray();
        $this->dispatch('attendanceUpdated', $this->attendanceData);
    }

    public function render()
    {
        $absensiQuery = Absensi::query();
        if($this->searchTerm) {
            $absensiQuery->whereHas('karyawan', function($query) {
                $query->where('nama', 'like', "%{$this->searchTerm}%");
            });
        }
        $todayData = $absensiQuery->where('tanggal', '=', $this->today)
            ->orderByDesc('waktu')
                ->paginate(10);

        $perPage = $todayData->perPage();
        $currentPage = $todayData->currentPage();
        $startNumber = ($currentPage - 1) * $perPage;

        return view(
            'livewire.pages.dashboard',
            compact('todayData', 'startNumber')
        );
    }
}
