<x-layouts.app>
    <div>
        @for ($month = $request['startMonth']; $month <= $request['endMonth']; $month++)
            @php
                $monthYearString = Carbon\Carbon::createFromDate($request['year'], $month, 1)->translatedFormat('F Y');
            @endphp
            <div class="flex h-full justify-center flex-col gap-4">
                <h1>Laporan presensi pada {{ $monthYearString }}</h1>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000;">
                    <thead>
                        <tr style="color: #374151; background-color: #E5E7EB;">
                            <th style="border: 1px solid #000000;">No</th>
                            <th style="border: 1px solid #000000;">Nama</th>
                            @foreach ($request['days'] as $day)
                                <th style="padding: 4px; text-align: center; border: 1px solid #000000; {{ $day['is_weekend'] ? 'background-color: #DC2626; color: #FAFAFA;' : '' }}">
                                    {{ $day['day_number'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workers as $karyawan)
                            <tr>
                                <td style="padding: 8px; border: 1px solid #000000;">{{ $loop->iteration }}</td>
                                <td style="padding: 8px; border: 1px solid #000000;">{{ $karyawan['nama'] }}</td>
                                @php
                                    $previousValue = 0;
                                @endphp
                                @foreach ($karyawan['absensi'] as $key => $absensi)
                                    @php
                                        $currentAttendanceMonth = explode('_', $key)[0];
                                    @endphp

                                    @foreach ($request['days'] as $day)
                                        @continue($previousValue >= $day['day_number'])
                                        @break(
                                            ((int) $now->format('j') < $day['day_number'] &&
                                            (int) $now->month <= (int) $month) ||
                                            (int) $month !== (int) $currentAttendanceMonth
                                        )

                                        @php
                                            $clockInStatus = isset($absensi['absen_masuk_status']) ? $absensi['absen_masuk_status'] : 'none';
                                            $clockOutStatus = isset($absensi['absen_keluar_status']) ? $absensi['absen_keluar_status'] : 'none';
                                            $isDateMatch = $absensi['tanggal'] === $day['day_number'];
                                            $isClockInOnTime = $clockInStatus === 'tepatWaktu';
                                            $isClockOutOnTime = $clockOutStatus === 'tepatWaktu';
                                            $isOnTime = $isClockInOnTime && $isClockOutOnTime && $isDateMatch;

                                            $isClockInLate = ($clockInStatus === 'terlambat') && $isDateMatch;
                                            $isLeaveEarly = ($clockOutStatus === 'lebihAwal') && $isDateMatch;

                                            $noClockIn = !isset($clockInStatus) && $isDateMatch;
                                            $noClockOut = !isset($clockOutStatus) && $isDateMatch;
                                            $isWeekend = $day['is_weekend'] ? true : false;
                                            $status = match (true) {
                                                $isWeekend => '',
                                                $noClockOut => 'tp',
                                                $noClockIn => 'tm',
                                                $isClockInLate => 'tl',
                                                $isLeaveEarly => 'pw',
                                                $isOnTime => 'v',
                                                default => 'a'
                                            };
                                            $statusStyling = match ($status) {
                                                'a' => 'background-color: #DC2626; color: #FAFAFA;', /* Red */
                                                'tp' => 'background-color: #FACC15;', /* Yellow */
                                                'v' => 'background-color: #16A34A;', /* Green */
                                                'tl' => 'background-color: #FACC15;', /* Yellow */
                                                'tm' => 'background-color: #FACC15;', /* Yellow */
                                                'pw' => 'background-color: #FACC15;', /* Yellow */
                                                default => ''
                                            }
                                        @endphp

                                        @if ($loop->parent->last)
                                            @php
                                                $previousValue = 0;
                                            @endphp
                                        @endif

                                        <td style="padding: 0; margin: 0; text-align: center; border: 1px solid #000000; {{ $statusStyling }}">
                                            {{ $status }}
                                        </td>
                                        @continue($loop->parent->last && ((int) $absensi['tanggal'] <= $day['day_number']))

                                        @if ($isDateMatch)
                                            @php
                                                $previousValue = $day['day_number'];
                                            @endphp
                                            @break
                                        @endif
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <table class="w-full">
                    <tr>
                        <th style="padding: 8px; border: 1px solid #000000; background-color: #D1D5DB;" colspan="12">Keterangan</th>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #000000;">V</td>
                        <td style="padding: 8px; border: 1px solid #000000;">Tepat Waktu</td>
                        <td style="padding: 8px; border: 1px solid #000000;">TL</td>
                        <td style="padding: 8px; border: 1px solid #000000;">Terlambat masuk</td>
                        <td style="padding: 8px; border: 1px solid #000000;">PW</td>
                        <td style="padding: 8px; border: 1px solid #000000;">Pulang sebelum waktunya</td>
                        <td style="padding: 8px; border: 1px solid #000000;">TM</td>
                        <td style="padding: 8px; border: 1px solid #000000;">Tidak absen masuk</td>
                        <td style="padding: 8px; border: 1px solid #000000;">TP</td>
                        <td style="padding: 8px; border: 1px solid #000000;">Tidak absen Pulang</td>
                        <td style="padding: 8px; border: 1px solid #000000;">A</td>
                        <td style="padding: 8px; border: 1px solid #000000;">Tidak absen</td>
                    </tr>
                </table>
            </div>
            @pageBreak
        @endfor
    </div>
</x-layouts.app>
