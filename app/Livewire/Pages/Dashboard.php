<?php

namespace App\Livewire\Pages;

use App\Enums\StatusAbsen;
use App\Enums\TipeAbsensi;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;

class Dashboard extends BasePage
{
    public $userCount;
    public $clockInCount;
    public $earlyClockOut;
    public $lateCount;
    public $absentCount;
    public $dateNow;
    public $today;
    public $searchTerm;
    public $jenisAbsenEnum;
    public $statusAbsenEnum;

    public function mount() {
        $this->jenisAbsenEnum = TipeAbsensi::class;
        $this->statusAbsenEnum = StatusAbsen::class;
        $this->today = Carbon::today();
        $this->dateNow = Carbon::now()->translatedFormat('l, j F Y');
        $this->userCount = Karyawan::count();
        $this->clockInCount = Karyawan::whereHas('absensi', function($query) {
            $query->whereDate('created_at', $this->today);
            $query->where('jenisAbsen', '=', TipeAbsensi::AbsenMasuk->value);
            $query->where('status', '=', StatusAbsen::TepatWaktu->value);
        })->count();
        $this->lateCount = Karyawan::whereHas('absensi', function($query) {
            $query->whereDate('created_at', $this->today);
            $query->where('jenisAbsen', '=', TipeAbsensi::AbsenMasuk->value);
            $query->where('status', '=', StatusAbsen::Terlambat->value);
        })->count();
        $this->earlyClockOut = Karyawan::whereHas('absensi', function($query) {
            $query->whereDate('created_at', $this->today);
            $query->where('jenisAbsen', '=', TipeAbsensi::AbsenKeluar->value);
            $query->where('status', '=', StatusAbsen::LebihAwal->value);
        })->count();
        $this->absentCount = Karyawan::whereDoesntHave('absensi', function($query) {
            $query->whereDate('created_at', $this->today);
        })->count();
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
                ->paginate(5);

        $perPage = $todayData->perPage();
        $currentPage = $todayData->currentPage();
        $startNumber = ($currentPage - 1) * $perPage;

        return view(
            'livewire.pages.dashboard',
            compact('todayData', 'startNumber')
        );
    }
}
