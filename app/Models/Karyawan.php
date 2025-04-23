<?php

namespace App\Models;

use App\Enums\Permission;
use App\Enums\RolePosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Karyawan extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'noTelepon',
        'username',
        'password',
        'jabatan_id',
        'foto',
        'permission'
    ];

    protected function casts(): array {
        return [
            'password' => 'hashed',
            'permission' => Permission::class
        ];
    }

    public function absensi() {
        return $this->hasMany(Absensi::class);
    }

    public function jabatan() {
        return $this->belongsTo(Jabatan::class);
    }
}
