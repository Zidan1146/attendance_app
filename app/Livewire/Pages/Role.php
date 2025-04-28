<?php

namespace App\Livewire\Pages;

use App\Models\Jabatan;
use App\Models\Karyawan;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class Role extends BasePage
{
    use WithPagination;

    public $search;

    #[Validate('required')]
    public $jabatan;

    #[Validate('required')]
    public $editJabatan;

    public function updatingSearch() {
        $this->resetPage();
    }

    public function mount() {
        parent::userInit();
        parent::adminAuthCheck();
        $this->search = '';
        $this->jabatan = '';
        $this->editJabatan = '';
    }

    public function create() {
        Jabatan::create(["nama" => $this->jabatan]);
        $this->reset('jabatan');
        $this->js('window.location.reload()');
    }

    public function edit($id) {
        Jabatan::findOrFail($id)->update(["nama" => $this->editJabatan]);
        $this->reset('editJabatan');
        $this->js('window.location.reload()');
    }

    public function delete($id) {
        Karyawan::where('jabatan_id', '=', $id)
        ->update([
            'jabatan_id' => null
        ]);

        Jabatan::findOrFail($id)->delete();
    }

    public function render()
    {
        $roleQuery = Jabatan::query();

        if($this->search) {
            $roleQuery->where('nama', 'like', "%{$this->search}%");
        }

        $roles = $roleQuery->paginate(10);
        $perPage = $roles->perPage();
        $currentPage = $roles->currentPage();
        $startNumber = ($currentPage - 1) * $perPage;

        return view('livewire.pages.role', compact('roles', 'startNumber'));
    }
}
