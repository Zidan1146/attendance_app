<?php
namespace App\Livewire\Pages;

use App\Livewire\Traits\WithRouteInfo;
use Livewire\Attributes\Session;
use Livewire\Component;

abstract class BasePage extends Component {
    use WithRouteInfo;

    #[Session('isCollapsed')]
    public $isCollapsed = false;

    public function toggleCollapse()
    {
        $this->isCollapsed = !$this->isCollapsed;
    }

    public function mount()
    {
        $this->mountWithRouteInfo();
    }
}
