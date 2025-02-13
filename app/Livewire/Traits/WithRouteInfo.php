<?php

namespace App\Livewire\Traits;

trait WithRouteInfo {
    public $routeName;

    public function mountWithRouteInfo()
    {
        $this->routeName = request()->route()->getName();
    }
}
