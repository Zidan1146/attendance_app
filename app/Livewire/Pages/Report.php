<?php

namespace App\Livewire\Pages;

use App\Enums\RolePosition;
use App\Utils\DateHelper;
use App\Models\Karyawan;
use Carbon\Carbon;
use Livewire\WithPagination;


class Report extends BasePage
{
    use WithPagination;

    public $days;
    public $selectedMonth;
    public $currentMonthName;
    public $months;
    public $year;
    public $roles;
    public $selectedRole = '';
    public function mount() {
        $this->months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => Carbon::createFromDate(null, $month, 1)->translatedFormat('F')];
        });

        $now = Carbon::now();
        $this->days = DateHelper::getMonthDays($now->year, $now->month);

        $this->currentMonthName = DateHelper::getMonthName($now->month);
        $this->selectedMonth = $now->month;
        $this->year = $now->year;

        $this->roles = RolePosition::cases();
    }

    public function updatingSelectedMonth($value) {
        $this->days = DateHelper::getMonthDays(null, $value);
        $this->currentMonthName = DateHelper::getMonthName($value);
    }

    public function updatedSelectedRole()
    {
        $this->resetPage();
    }

    public function render()
    {
        $workersQuery = Karyawan::query();

        if ($this->selectedRole) {
            $workersQuery->where('jabatan', '=', $this->selectedRole);
        }

        $workers = $workersQuery->paginate(5);
        return view(
            'livewire.pages.report',
            compact(
                'workers'
            )
        );
    }
}
