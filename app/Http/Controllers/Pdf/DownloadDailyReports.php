<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Utils\DailyAttendanceHelper;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Enums\Unit;

use function Spatie\LaravelPdf\Support\pdf;

class DownloadDailyReports extends Controller
{
    public function __invoke(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $workers = DailyAttendanceHelper::getAttendanceData($startDate, $endDate);
        dd($workers);

        return Pdf()
            ->view('pdf.report.daily.index', [
                'workers' => $workers,
                'startDate' => $startDate,
                'endDate' => $endDate
            ])
            ->format(Format::A4)
            ->orientation(Orientation::Landscape)
            ->margins(0.25,0.25,0.25,0.25, Unit::Centimeter)
            ->name('dailytest.pdf')
            ->download();
    }
}
