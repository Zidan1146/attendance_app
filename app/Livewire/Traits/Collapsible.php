<?php

namespace App\Livewire\Traits;

trait Collapsible {
    public $isCollapsed = false;

    public function toggleCollapse()
    {
        $this->isCollapsed = !$this->isCollapsed;
    }
}
