<?php
namespace App\Utils;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DateHelper {
    public static function getMonthDays($year, $month){
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $days = [];
        foreach (CarbonPeriod::create($start, $end) as $date) {
            $days[] = [
                'day_number' => (int) $date->format('j'),
                'is_weekend' => $date->isSunday() || $date->isSaturday(),
            ];
        }

        return $days;
    }

    public static function getMonthName($month) {
        $date = Carbon::create(null, $month, 1)->locale('id');

        return $date->translatedFormat('F');
    }
}

