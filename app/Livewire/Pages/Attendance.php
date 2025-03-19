<?php

namespace App\Livewire\Pages;

use App\Enums\RolePosition;
use App\Enums\StatusAbsen;
use App\Enums\TipeAbsensi;
use App\Models\Absensi;

class Attendance extends BasePage
{
    public $roles;
    public $availableDates;
    public $attendaceTypes;
    public $attendanceStatuses;
    public $selectedRole;
    public $selectedAttendanceType;
    public $selectedAttendanceStatus;
    public $startDate;
    public $endDate;
    public $jenisAbsenEnum;
    public $statusAbsenEnum;
    public $searchTerm;

    public function updating($property) {
        $isDataSelection =
            $property === 'selectedRole' ||
            $property === 'selectedAttendanceType' ||
            $property === 'selectedAttendanceStatus' ||
            $property === 'startDate' ||
            $property === 'endDate';

        if(isset($property) && $isDataSelection) {
            $this->resetPage();
        }
    }
    public function mount() {
        parent::authCheck();
        $this->roles = RolePosition::cases();
        $this->attendaceTypes = TipeAbsensi::cases();
        $this->attendanceStatuses = StatusAbsen::cases();
        $this->availableDates = Absensi::distinct()->orderBy('tanggal')->pluck('tanggal');
        $this->jenisAbsenEnum = TipeAbsensi::class;
        $this->statusAbsenEnum = StatusAbsen::class;
    }

    public function render()
    {
        $absensiQuery = Absensi::query();

        if($this->selectedRole) {
            $absensiQuery->whereHas('karyawan', function($query) {
                $query->where('jabatan', '=', $this->selectedRole);
            });
        }
        if($this->selectedAttendanceType) {
            $absensiQuery->where('jenisAbsen', '=', $this->selectedAttendanceType);
        }
        if($this->selectedAttendanceStatus) {
            $absensiQuery->where('status', '=', $this->selectedAttendanceStatus);
        }
        if($this->startDate && $this->endDate) {
            $absensiQuery->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }
        if($this->searchTerm) {
            $absensiQuery->whereHas('karyawan', function($query) {
                $query->where('nama', 'like', "%{$this->searchTerm}%");
            });
        }

        $dataAbsensi = $absensiQuery->orderBy('tanggal')->paginate(10);
        $perPage = $dataAbsensi->perPage();
        $currentPage = $dataAbsensi->currentPage();
        $startNumber = ($currentPage - 1) * $perPage;

        return view(
            'livewire.pages.attendance',
            compact('dataAbsensi', 'startNumber')
        );
    }
}
