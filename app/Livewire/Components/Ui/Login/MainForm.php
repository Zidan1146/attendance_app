<?php

namespace App\Livewire\Components\Ui\Login;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class MainForm extends Component
{
    #[Validate('required', message: 'Nama pengguna tidak boleh kosong.')]
    public $username;

    #[Validate('required', message: 'Kata sandi tidak boleh kosong.')]
    public $password;

    public function login_() {
        $credentials = $this->validate();

        if(!Auth::attempt($credentials)) {
            session()->flash('error', 'Nama pengguna atau kata sandi salah.');
            return;
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.components.ui.login.main-form');
    }
}
