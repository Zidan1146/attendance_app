<?php

namespace App\Models;

use App\Casts\TimeCast;
use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'tanggal',
        'waktu',
        'jenisAbsen',
        'status',
        'deskripsi'
    ];

    protected function casts(): array {
        return [
            'tanggal' => 'date',
            'waktu' => TimeCast::class,
            'jenisAbsen' => AttendanceType::class,
            'status' => AttendanceStatus::class
        ];
    }

    public function karyawan() {
        return $this->belongsTo(Karyawan::class);
    }
}
