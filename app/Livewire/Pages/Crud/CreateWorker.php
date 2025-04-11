<?php

namespace App\Livewire\Pages\Crud;

use App\Enums\RolePosition;
use App\Models\Karyawan;
use App\Livewire\Forms\CreateWorkerForm as WorkerForm;
use App\Livewire\Pages\BasePage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateWorker extends BasePage
{
    public WorkerForm $form;
    public $roles;

    public function mount() {
        parent::authCheck();
        $this->roles = RolePosition::cases();
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
