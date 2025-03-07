<?php

namespace App\Models;

use App\Casts\TimeCast;
use App\Enums\StatusAbsen;
use App\Enums\TipeAbsensi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal',
        'waktu',
        'jenisAbsen',
        'status',
        'deskripsi',
        'karyawan_id'
    ];

    protected function casts(): array {
        return [
            'tanggal' => 'date',
            'waktu' => TimeCast::class,
            'jenisAbsen' => TipeAbsensi::class,
            'status' => StatusAbsen::class
        ];
    }

    public function karyawan() {
        return $this->belongsTo(Karyawan::class);
    }
}
