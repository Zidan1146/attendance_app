<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Auth;

trait WithUserInfo {
    public $user;

    public function mountWithUserInfo()
    {
        $this->user = Auth::user();
    }
}
