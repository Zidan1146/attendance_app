<?php

namespace App\View\Components\Dashboard;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DateTime extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $now = Carbon::now();
        $dateNow = $now->translatedFormat('l, j F Y');
        return view('components.dashboard.date-time', compact('dateNow'));
    }
}
