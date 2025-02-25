<?php
    namespace App\Enums;

    enum AttendanceStatus:string {
        case TidakDiketahui = "tidakDiketahui";
        case TepatWaktu = "tepatWaktu";
        case Terlambat = "terlambat";
        case LebihAwal = "lebihAwal";
        case TidakAbsen = "tidakAbsen";
    }
