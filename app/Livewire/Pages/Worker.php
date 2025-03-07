<?php

namespace App\Livewire\Pages;

use App\Models\Karyawan;
use Livewire\WithPagination;

class Worker extends BasePage
{
    use WithPagination;

    public $search;

    public function updatingSearch() {
        $this->resetPage();
    }

    public function delete($id) {
        Karyawan::destroy($id);

        session()->flash('message', 'Data deleted successful');
        $this->resetPage();
    }

    public function render()
    {
        $workers = Karyawan::where('nama', 'like', "%{$this->search}%")
            ->orWhere('username', 'like', "%{$this->search}%")
            ->orWhere('alamat', 'like', "%{$this->search}%")
            ->orWhere('noTelepon', 'like', "%{$this->search}%")
            ->paginate(10);
        return view('livewire.pages.worker', compact('workers'));
    }
}
