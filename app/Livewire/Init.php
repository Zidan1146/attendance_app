<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Init extends Component
{
    public function mount() {
        if(Auth::check()) {
            $this->redirectRoute('dashboard');
        } else {
            $this->redirectRoute('login');
        }
    }
}
