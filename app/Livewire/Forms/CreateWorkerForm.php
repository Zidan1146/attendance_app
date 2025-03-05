<?php

namespace App\Livewire\Forms;

use App\Enums\RolePosition;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateWorkerForm extends Form
{
    #[Validate('required')]
    public $nama;

    #[Validate('required')]
    public $alamat;

    #[Validate('required|regex:/^(8\d{2})[-\s]?\d{3,4}[-\s]?\d{3,4}$/')]
    public $noTelepon;

    #[Validate('required')]
    public $jabatan = RolePosition::HR->value;

    #[Validate('required|unique:karyawans,username|regex:/^[a-zA-Z0-9_]{3,16}$/')]
    public $username;

    #[Validate('required|min:8')]
    public $password;
}
