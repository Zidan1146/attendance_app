<?php
    namespace App\Enums;

    enum StatusAbsen:string {
        case TidakDiketahui = "tidakDiketahui";
        case TepatWaktu = "tepatWaktu";
        case Terlambat = "terlambat";
        case LebihAwal = "lebihAwal";
        case TidakAbsen = "tidakAbsen";
    }
