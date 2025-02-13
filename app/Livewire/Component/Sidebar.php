<?php

namespace App\Livewire\Component;

use Livewire\Component;

class Sidebar extends Component
{

    public $isCollapsed = false;

    public function toggleSidebar() {
        $this->isCollapsed = !$this->isCollapsed;
    }

    public function render()
    {
        return view('livewire.component.sidebar');
    }
}
