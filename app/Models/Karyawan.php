<?php

namespace App\Models;

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

    public function groupedAbsensi()
    {
        return $this->absensi()
            ->selectRaw('tanggal,
                MAX(CASE WHEN jenisAbsen = "absenMasuk" THEN status END) AS absen_masuk_status,
                MAX(CASE WHEN jenisAbsen = "absenKeluar" THEN status END) AS absen_keluar_status')
            ->groupBy('tanggal');
    }
}
