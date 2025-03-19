<?php

namespace App\Livewire\Pages;

use App\Enums\RolePosition;
use App\Models\Absensi;
use App\Models\Karyawan;
use Livewire\WithPagination;

class Worker extends BasePage
{
    use WithPagination;

    public $search;
    public $roles;
    public $selectedRole;

    public function mount() {
        parent::authCheck();
        $this->roles = RolePosition::cases();
    }

    public function updatingSearch() {
        $this->resetPage();
    }

    public function delete($id) {
        Absensi::where('karyawan_id', '=', $id)->delete();
        Karyawan::destroy($id);

        session()->flash('message', 'Data deleted successful');
        $this->js('window.location.reload()');
    }

    public function render()
    {
        $workerQuery = Karyawan::query();

        if($this->selectedRole) {
            $workerQuery->where('jabatan', '=', $this->selectedRole);
        }

        if($this->search) {
            $workerQuery->where(function ($query) {
                $query->where('nama', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%")
                    ->orWhere('alamat', 'like', "%{$this->search}%")
                    ->orWhere('noTelepon', 'like', "%{$this->search}%");
            });
        }

        $workers = $workerQuery->paginate(10);

        $perPage = $workers->perPage();
        $currentPage = $workers->currentPage();
        $startNumber = ($currentPage - 1) * $perPage;

        return view('livewire.pages.worker', compact('workers', 'startNumber'));
    }
}
