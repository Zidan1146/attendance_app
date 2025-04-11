<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Utils\MonthlyAttendanceHelper;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Enums\Unit;

use function Spatie\LaravelPdf\Support\pdf;

class DownloadMonthlyReports extends Controller
{
    public function __invoke(Request $request)
    {
        $now = $request->input('now');
        $carbonNow = \Carbon\Carbon::parse($now);
        $workers = MonthlyAttendanceHelper::getData(
            $request->get('role'),
            $request->get('year'),
            $request->get('startMonth'),
            $request->get('endMonth'),
        );

        return Pdf()
            ->view('pdf.report.monthly.index', [
                'request' => $request->all(),
                'now' => $carbonNow,
                'workers' => $workers
            ])
            ->format(Format::A4)
            ->orientation(Orientation::Landscape)
            ->margins(0.25,0.25,0.25,0.25, Unit::Centimeter)
            ->name('sometest.pdf')
            ->download();
    }
}
