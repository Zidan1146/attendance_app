<?php
namespace App\Livewire\Pages;

use App\Enums\Permission;
use App\Livewire\Traits\WithRouteInfo;
use App\Livewire\Traits\WithUserInfo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

abstract class BasePage extends Component {
    use WithRouteInfo;
    use WithPagination;
    use WithUserInfo;

    #[Session('isCollapsed')]
    public $isCollapsed = false;

    public function userInit() {
        $this->mountWithRouteInfo();
        $this->mountWithUserInfo();
        $this->authCheck();
    }

    public function toggleCollapse() {
        $this->isCollapsed = !$this->isCollapsed;
    }

    public function logout_() {
        Auth::logout();
        $this->redirectRoute('login');
    }

    public function authCheck() {
        if(!Auth::check()) {
            $this->redirectRoute('login');
            return;
        }
    }

    public function adminAuthCheck() {
        $this->authCheck();

        if($this->user->permission->value === Permission::User->value) {
            $this->redirectIntended(route('dashboard'));
        }
    }

    public function superAdminAuthCheck() {
        $this->authCheck();

        if($this->user->permission->value !== Permission::SuperAdmin->value) {
            $this->redirectIntended(route('dashboard'));
        }
    }
}
