<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{

    #[Validate('required')]
    public $username;

    #[Validate('required')]
    public $password;

    public function mount() {
        if(Auth::check()) {
            $this->redirectRoute('dashboard');
        }
    }

    public function login_() {
        $credentials = $this->validate();

        if(!Auth::attempt($credentials)) {
            session()->flash('error', 'The provided credentials do not match our records.');
            return;
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}
