<?php

namespace App\Livewire\Pages;

use App\Models\Karyawan;
use Livewire\WithPagination;

class Worker extends BasePage
{
    use WithPagination;

    public function delete($id) {
        Karyawan::destroy($id);

        session()->flash('message', 'Data deleted successful');
        $this->resetPage();
    }

    public function render()
    {
        $workers = Karyawan::paginate(10);
        return view('livewire.pages.worker', compact('workers'));
    }
}
