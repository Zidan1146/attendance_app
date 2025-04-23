<?php

namespace App\Livewire\Forms;

use App\Enums\Permission;
use App\Enums\RolePosition;
use App\Models\Karyawan;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditWorkerForm extends Form
{
    #[Validate('required')]
    public $nama;

    #[Validate('required')]
    public $alamat;

    #[Validate('required|regex:/^(8\d{2})[-\s]?\d{3,4}[-\s]?\d{3,4}$/')]
    public $noTelepon;

    #[Validate('required')]
    public $jabatan_id;

    #[Validate('required|regex:/^[a-zA-Z0-9_]{3,16}$/')]
    public $username;

    #[Validate('nullable|min:8')]
    public $password;

    #[Validate('required')]
    public $permission = Permission::User->value;

    #[Validate('nullable|max:2048|mimes:jpg,jpeg,png,webp')]
    public $foto;

    public function update($id) {
        $this->validate();
        $excludedProperties = [];

        $this->noTelepon = "+62{$this->noTelepon}";
        if($this->foto) {
            $this->foto = $this->foto->store('avatar', 'public');
        }
        if(!$this->password) {
            $excludedProperties[] = 'password';
        }
        if(!$this->foto) {
            $excludedProperties[] = 'foto';
        }

        $formData = $this->except($excludedProperties);

        Karyawan::findOrFail($id)->update($formData);
    }
}
