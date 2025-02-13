<?php

namespace App\Livewire\Component;

use App\Livewire\Traits\Collapsible;
use Livewire\Component;

class Sidebar extends Component
{

    use Collapsible;

    public function render()
    {
        return view('livewire.component.sidebar');
    }
}
