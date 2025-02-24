<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class MainLayout extends Component
{
    public $isCollapsed = false;

    public function toggleCollapse()
    {
        $this->isCollapsed = !$this->isCollapsed;
    }

    public function render()
    {
        return view('livewire.layout.app');
    }
}
