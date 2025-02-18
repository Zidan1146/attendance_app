<?php

namespace App\Livewire\Pages\Auth;

use App\Enums\RolePosition;
use Livewire\Component;

class Register extends Component
{
    public $selectedRole = RolePosition::HR->value;
    public $isHr = true;

    public function updating($property, $value) {
        if($property === 'selectedRole') {
            $this->isHr = $value === RolePosition::HR->value;
        }
    }
    public function render()
    {
        $roles = RolePosition::cases();

        return view('livewire.pages.auth.register', compact('roles'));
    }
}
