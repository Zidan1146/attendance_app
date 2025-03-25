<?php

namespace App\Livewire\Pages;

use App\Enums\StatusAbsen;
use App\Enums\TipeAbsensi;
use App\Exports\DailyExport;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends BasePage
{
    public $userCount;
    public $clockInCount;
    public $clockOutCount;
    public $earlyClockOut;
    public $lateCount;
    public $absentCount;
    public $dateNow;
    public $today;
    public $searchTerm;
    public $jenisAbsenEnum;
    public $statusAbsenEnum;
    public $attendanceData;
    public $categories;
    public $years;
    public $selectedYear;

    public function mount() {
        parent::authCheck();
        $this->years = Absensi::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $this->selectedYear = Carbon::now()->year;
        $this->jenisAbsenEnum = TipeAbsensi::class;
        $this->statusAbsenEnum = StatusAbsen::class;
        $this->categories = $this->statusAbsenEnum::cases();
        $this->today = Carbon::today();
        $this->dateNow = Carbon::now()->translatedFormat('l, j F Y');
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

    public function loadAttendanceData() {
        $data = Absensi::whereYear('tanggal', '=', $this->selectedYear)
            ->get()
            ->groupBy(fn ($a) => Carbon::parse($a->tanggal)->format('F'))
            ->map(fn ($records) => $records->groupBy('status')->map->count());

        $this->attendanceData = $data->toArray();
        $this->dispatch('attendanceUpdated', $this->attendanceData);
    }

    public function exportXls() {
        $now = Carbon::now()->startOfDay();
        return Excel::download(new DailyExport($now->copy()->format('Y-m-d H:i:s')), "absensi_{$now->translatedFormat('j F Y')}.xlsx");
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
