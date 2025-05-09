<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LoginSideImage extends Component
{
    /**
     * Create a new component instance.
     */

    public $src;

    public function __construct($src) {
        $this->src = $src;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.login-side-image', ['src' => $this->src]);
    }
}
