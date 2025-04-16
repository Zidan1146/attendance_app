<?php

namespace App\Livewire\Pages\Crud;

use App\Enums\RolePosition;
use App\Livewire\Forms\EditWorkerForm as WorkerForm;
use App\Livewire\Pages\BasePage;
use App\Models\Karyawan;
class EditWorker extends BasePage
{
    public WorkerForm $form;
    public $worker;
    public $roles;

    public function mount($id) {
        parent::authCheck();
        $this->worker = Karyawan::findOrFail($id);
        $this->roles = RolePosition::cases();
        $worker = $this->worker;

        $noTelepon = $this->splitPhoneNumber($worker->noTelepon);
        if(isset($noTelepon) && count($noTelepon) > 1) {
            $noTelepon = $noTelepon[1];
        }

        $this->form->nama = $worker->nama;
        $this->form->alamat = $worker->alamat;
        $this->form->noTelepon = $noTelepon ?: $worker->noTelepon;
        $this->form->jabatan = $worker->jabatan;
        $this->form->username = $worker->username;
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
