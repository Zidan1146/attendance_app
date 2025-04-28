<?php

namespace App\Livewire\Pages\Crud;

use App\Enums\Permission;
use App\Enums\RolePosition;
use App\Models\Karyawan;
use App\Livewire\Forms\CreateWorkerForm as WorkerForm;
use App\Livewire\Pages\BasePage;
use App\Models\Jabatan;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\WithFileUploads;

class CreateWorker extends BasePage
{
    use WithFileUploads;
    public WorkerForm $form;
    public $roles;
    public $permissions;
    public $isSuperAdmin;

    public function mount() {
        parent::userInit();
        $this->roles = Jabatan::all();
        $this->permissions = Permission::cases();
        $this->form->jabatan_id = Jabatan::orderBy('id')->first()->id;
        $this->isSuperAdmin = $this->user->permission->value === Permission::SuperAdmin->value;
    }

    public function updated($name, $value)
    {
        $this->$name = $value;
    }

    private function clearVariables() {
        foreach($this->form->all() as $property) {
            if($property === 'jabatan') {
                $this->$property = RolePosition::HR->value;
                continue;
            }

            $this->$property = '';
        }
    }

    public function rules() {
        return [
            'nama' => 'required',
            'alamat' => 'required',
            'noTelepon' => 'required|regex:/^(8\d{2})[-\s]?\d{3,4}[-\s]?\d{3,4}$/',
            'jabatan' => Rule::in(
                RolePosition::Animator->value,
                RolePosition::HR->value,
                RolePosition::IT->value
            ),
            'username' => 'required|unique:karyawans,username|regex:/^[a-zA-Z0-9_]{3,16}$/',
            'password' => [
                'required',
                Password::min(8)
            ]
        ];
    }

    public function store() {
        $this->form->validate();

        $this->form->noTelepon = "+62{$this->form->noTelepon}";

        $this->form->foto = $this->form->foto->store('avatar', 'public');

        Karyawan::create(
            $this->form->all()
        );

        $this->clearVariables();
        $this->redirectRoute('worker');
    }

    public function render()
    {
        $roles = $this->roles;
        return view('livewire.pages.crud.create-worker', compact('roles'));
    }
}
