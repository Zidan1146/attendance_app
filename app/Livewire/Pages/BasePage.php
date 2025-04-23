<?php
namespace App\Livewire\Pages;

use App\Livewire\Traits\WithRouteInfo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

abstract class BasePage extends Component {
    use WithRouteInfo;
    use WithPagination;

    #[Session('isCollapsed')]
    public $isCollapsed = false;

    public function toggleCollapse()
    {
        $this->isCollapsed = !$this->isCollapsed;
    }

    public function logout_() {
        Auth::logout();
        $this->redirectRoute('login');
    }

    public function authCheck() {
        if(!Auth::check()) {
            $this->redirectRoute('login');
        }
    }
}
