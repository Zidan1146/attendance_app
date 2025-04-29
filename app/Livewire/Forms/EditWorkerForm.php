<?php

namespace App\Livewire\Forms;

use App\Enums\Permission;
use App\Models\Karyawan;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditWorkerForm extends Form
{
    #[Validate('required', message: 'Nama lengkap tidak boleh kosong.')]
    public $nama;

    #[Validate('required', message: 'Alamat tidak boleh kosong.')]
    public $alamat;

    #[Validate('required', message: 'No telepon tidak boleh kosong.')]
    #[Validate('regex:/^(8\d{2})[-\s]?\d{3,4}[-\s]?\d{3,4}$/')]
    public $noTelepon;

    #[Validate('required', message: 'Jabatan tidak boleh kosong.')]
    public $jabatan_id;

    #[Validate('required', message: 'Nama pengguna tidak boleh kosong.')]
    #[Validate('regex:/^[a-zA-Z0-9_]{3,16}$/')]
    public $username;

    #[Validate('nullable')]
    #[Validate('min:8', message: 'Kata sandi baru barus mempunyai minimal 8 karakter.')]
    public $password;

    #[Validate('required', message: 'Permission tidak boleh kosong.')]
    public $permission = Permission::User->value;

    #[Validate('nullable')]
    #[Validate('max:2048', message: 'Ukuran gambar tidak boleh melebihi 2MB')]
    #[Validate('mimes:jpg,jpeg,png,webp', message: "Input hanya menerima gambar berjenis 'jpg', 'jpeg', 'png' dan 'webp'")]
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
