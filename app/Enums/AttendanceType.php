<?php
    namespace App\Enums;

    enum AttendanceType:string {
        case AbsenMasuk = "absenMasuk";
        case AbsenKeluar = "absenKeluar";
        case Lembur = "lembur";
    }
