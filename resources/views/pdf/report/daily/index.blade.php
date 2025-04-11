<x-layouts.app>
    @php
        $startDate = Carbon\Carbon::createFromDate($startDate);
        $endDate = Carbon\Carbon::createFromDate($endDate);
    @endphp
    <div style="display: flex; flex-direction: column; justify-content: center;">
        @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
            <div class="">
                <h1>Laporan presensi pada {{ $date->translatedFormat('j F Y') }}</h1>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #d1d5db;">
                    <thead>
                        <tr style="background-color: #e5e7eb; color: #374151;">
                            <th style="border: 1px solid #d1d5db; padding: 8px;">No</th>
                            <th style="border: 1px solid #d1d5db; padding: 8px;">Nama</th>
                            <th style="border: 1px solid #d1d5db; padding: 8px;">Jam Masuk</th>
                            <th style="border: 1px solid #d1d5db; padding: 8px;">Jam Keluar</th>
                            <th style="border: 1px solid #d1d5db; padding: 8px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workers as $index => $worker)
                            <tr>
                                <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">{{ $index + 1 }}</td>
                                <td style="border: 1px solid #d1d5db; padding: 8px;">{{ $worker['nama'] }}</td>
                                <td style="border: 1px solid #d1d5db; padding: 8px;">{{ $worker['todayStat']['jamMasuk'] ?? '-' }}</td>
                                <td style="border: 1px solid #d1d5db; padding: 8px;">{{ $worker['todayStat']['jamKeluar'] ?? '-' }}</td>

                                @php
                                    $status = $worker['todayStat']['status'] ?? '-';
                                    $backgroundColor = match ($status) {
                                        'Tidak Absen' => '#DC2626', // Red
                                        'Tepat Waktu' => '#16A34A', // Green
                                        'Pulang Lebih Awal', 'Tidak Absen Masuk', 'Tidak Absen Pulang', 'Terlambat' => '#FACC15', // Yellow
                                        default => '#ffffff', // White (default)
                                    };
                                    $textColor = in_array($status, ['Tidak Absen', 'Tepat Waktu']) ? '#ffffff' : '#000000';
                                @endphp

                                <td style="border: 1px solid #d1d5db; padding: 8px; background-color: {{ $backgroundColor }}; color: {{ $textColor }}; text-align: center;">
                                    {{ $status }}
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        @pageBreak
        @endfor
    </div>
</x-layouts.app>
