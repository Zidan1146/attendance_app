<?php

namespace App\Livewire\Components\Ui;

use Livewire\Component;

class TextInput extends Component
{
    public $id;
    public $model;
    public $class;
    public $placeholder;

    public function render()
    {
        return view('livewire.components.ui.text-input');
    }
}
