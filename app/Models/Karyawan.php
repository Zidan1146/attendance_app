<?php

namespace App\Models;

use App\Enums\RolePosition;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'noTelepon',
        'jabatan',
        'username',
        'password'
    ];

    protected function casts(): array {
        return [
            'jabatan' => RolePosition::class,
            'password' => 'hashed'
        ];
    }

    public function absensi() {
        return $this->hasMany(Absensi::class);
    }
}
