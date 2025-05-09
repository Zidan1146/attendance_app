<?php

namespace App\Livewire\Components\Ui\Dashboard;

use Livewire\Component;

class Chart extends Component
{
    public $years;
    public $attendanceData;
    public $selectedYear;
    public $categories;

    public function updatedSelectedYear() {
        $this->dispatch('loadChartData', $this->selectedYear);
    }

    public function render()
    {
        return view('livewire.components.ui.dashboard.chart');
    }
}
