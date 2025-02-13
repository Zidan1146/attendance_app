<?php

namespace App\Livewire\Pages;

use App\Livewire\Traits\Collapsible;
use App\Livewire\Traits\WithRouteInfo;
use Livewire\Component;

class Dashboard extends Component
{
    use Collapsible, WithRouteInfo;

    public function mount()
    {
        $this->mountWithRouteInfo();
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
