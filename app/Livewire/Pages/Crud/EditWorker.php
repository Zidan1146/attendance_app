<?php

namespace App\Livewire\Pages\Crud;

use App\Enums\Permission;
use App\Livewire\Forms\EditWorkerForm as WorkerForm;
use App\Livewire\Pages\BasePage;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Livewire\WithFileUploads;

class EditWorker extends BasePage
{
    use WithFileUploads;
    public WorkerForm $form;
    public $worker;
    public $roles;
    public $permissions;
    public $existingAvatar;

    public function mount($id) {
        parent::authCheck();
        $this->worker = Karyawan::findOrFail($id);
        $this->roles = Jabatan::all();
        $this->permissions = Permission::cases();
        $worker = $this->worker;

        $noTelepon = $this->splitPhoneNumber($worker->noTelepon);
        if(isset($noTelepon) && count($noTelepon) > 1) {
            $noTelepon = $noTelepon[1];
        }

        $this->form->nama = $worker->nama;
        $this->form->alamat = $worker->alamat;
        $this->form->noTelepon = $noTelepon ?: $worker->noTelepon;
        $this->form->jabatan_id = $this->form->jabatan_id ? $worker->jabatan_id : Jabatan::orderBy('id')->first()->id;
        if($worker->foto) {
            $this->existingAvatar = $worker->foto;
        }
        $this->form->username = $worker->username;
        $this->form->permission = $worker->permission->value;
    }

    public function update() {
        $this->form->update($this->worker->id);
        $this->redirectRoute('worker');
    }

    public function render()
    {
        return view('livewire.pages.crud.edit-worker');
    }

    private function splitPhoneNumber($phone) {
        preg_match('/^(\+\d{1,3})(8\d+)$/', $phone, $matches);
        return $matches ? [$matches[1], $matches[2]] : null;
    }
}
