<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Stats extends Component
{
    public $clockInCount = 0;
    public $clockOutCount = 0;
    public $lateCount = 0;
    public $earlyClockOut = 0;
    public $isAdmin = false;
    public $userCount = 0;
    public $absentCount = 0;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $clockInCount,
        $clockOutCount,
        $lateCount,
        $absentCount,
        $userCount,
        $isAdmin
    )
    {
        $this->clockInCount = $clockInCount;
        $this->clockOutCount = $clockOutCount;
        $this->lateCount = $lateCount;
        $this->absentCount = $absentCount;
        $this->userCount = $userCount;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view(
            'components.dashboard.stats', [
                'clockInCount' => $this->clockInCount,
                'clockOutCount' => $this->clockOutCount,
                'lateCount' => $this->lateCount,
                'absentCount' => $this->absentCount,
                'userCount' => $this->userCount,
                'isAdmin' => $this->isAdmin,
            ]
        );
    }
}
