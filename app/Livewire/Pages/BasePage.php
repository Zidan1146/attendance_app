<?php
namespace App\Livewire\Pages;

use App\Livewire\Traits\WithRouteInfo;
use Illuminate\Support\Facades\Auth;
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

    public function logout_() {
        Auth::logout();
        $this->redirectRoute('login');
    }
}
