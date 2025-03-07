<?php
    namespace App\Enums;

    enum TipeAbsensi:string {
        case AbsenMasuk = "absenMasuk";
        case AbsenKeluar = "absenKeluar";
        case Lembur = "lembur";
    }
