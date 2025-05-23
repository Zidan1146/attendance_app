<?php

namespace App\Livewire\Pages\Auth;

use App\Enums\RolePosition;
use App\Models\Karyawan;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class Register extends Component
{

    #[Validate('required')]
    public $nama;

    #[Validate('required')]
    public $alamat;

    #[Validate('required|regex:/^(8\d{2})[-\s]?\d{3,4}[-\s]?\d{3,4}$/')]
    public $noTelepon;

    #[Validate('required')]
    public $jabatan = RolePosition::HR->value;

    #[Validate('required|unique:karyawans,username|regex:/^[a-zA-Z0-9_]{3,16}$/')]
    public $username;

    #[Validate('required|min:8')]
    public $password;

    public function mount() {
        if(Auth::check()) {
            $this->redirectRoute('dashboard');
        }
    }

    public function updated($name, $value)
    {
        $this->$name = $value;
    }

    private function clearVariables() {
        foreach($this->all() as $property) {
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

    public function register() {
        $this->validate();

        $this->noTelepon = "+62$this->noTelepon";
        Karyawan::create(
            $this->all()
        );

        return back()->with("message", "Success");
    }

    public function render()
    {
        $roles = RolePosition::cases();

        return view('livewire.pages.auth.register', compact('roles'));
    }
}
