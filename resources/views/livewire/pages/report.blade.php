@extends('livewire.layout.app')

@section('content')
    <h1 class="text-2xl">Rekap Presensi</h1>

    <div class="flex flex-col justify-center">
        <div class="flex justify-start w-2/3">
            <div class="gap-3 form-control">
                <div class="flex w-full gap-2">
                    <label class="w-48">
                        <div class="label">
                            <span class="label-text">Jabatan</span>
                        </div>
                        <select name="" id="" wire:model.live="selectedRole" class="w-full select bg-neutral select-bordered">
                            <option value="">Semua</option>
                            @foreach ($roles as $jabatan)
                                <option value="{{ $jabatan->value }}">{{ $jabatan->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="label">
                        <span class="label-text">Periode</span>
                    </label>
                    <div class="join join-horizontal">
                        <select name="" id="" class="w-full select bg-neutral select-bordered join-item" wire:model.live="selectedMonth">
                            @foreach ($months as $monthNumber => $monthName)
                                <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                            @endforeach
                        </select>
                        <select name="" id="" class="w-40 select bg-neutral select-bordered join-item" wire:model.live="selectedYear">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $year === $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col h-full overflow-x-auto gap-y-3">
            @if (count($workers) > 0)
                <div class="flex justify-between w-full h-full align-bottom">
                    <span class="w-3/5 mt-auto text-sm italic text-primary">
                        Keterangan: v = tepat waktu, tl = terlambat masuk, pw = pulang sebelum waktunya, tm = tidak absen masuk, tp = tidak absen pulang, a = tidak absen
                    </span>
                    <div class="flex h-full gap-3">
                        <label class="flex items-center w-full gap-2 input input-bordered bg-neutral">
                            <input type="text" name="" id="" placeholder="Search" class="grow"
                                wire:model.live.debounce.1000ms="searchTerm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </label>
                        <button class="btn btn-primary" onclick="export_modal.showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-1 size-6">
                                <path
                                    d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                <path
                                    d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                            </svg>

                            Export
                        </button>
                        <dialog wire:ignore.self id="export_modal" class="modal">
                            <div class="modal-box">
                                <h3 class="text-lg font-bold">Export sebagai xlsx</h3>
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col">
                                        <label for="" class="label">Jenis Periode</label>
                                        <select name="" id="" wire:model.live="reportPeriodType" class="join-item select bg-neutral">
                                            <option value="monthly">Bulanan</option>
                                            <option value="daily">Harian</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-between">
                                        <div class="">
                                            <label for="" class="label">Periode</label>
                                            <div class="join">
                                                @if ($reportPeriodType === 'monthly')
                                                    <select name="" id="" class="join-item select bg-neutral" wire:model.live="selectedExportStartMonth" wire:key="start-month">
                                                        @foreach ($months as $monthNumber => $monthName)
                                                            <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select name="" id="" class="join-item select bg-neutral" wire:model.live="selectedExportEndMonth" wire:key="start-end">
                                                        @foreach ($months as $monthNumber => $monthName)
                                                            <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="" id="" class="join-item select bg-neutral" wire:model.live="selectedStartDate" wire:key="start-date">
                                                        <option value="" disabled>- Dari Tanggal -</option>
                                                        @foreach ($availableDates as $date)
                                                            <option value="{{ $date }}">{{ $date->translatedFormat('j F Y') }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select name="" id="" class="join-item select bg-neutral" wire:model.live="selectedEndDate" wire:key="end-date">
                                                        <option value="" disabled>- Sampai Tanggal -</option>
                                                        @foreach ($availableDates as $date)
                                                            <option value="{{ $date }}">{{ $date->translatedFormat('j F Y') }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($reportPeriodType === 'monthly')
                                            <div>
                                                <label for="" class="label">Tahun</label>
                                                <select name="" id="" class="join-item select bg-neutral" wire:model="selectedExportYear">
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year }}" {{ $year === $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div>
                                            <label for="" class="label">Jabatan</label>
                                            <select name="" id="" class="join-item select bg-neutral" wire:model="selectedExportRole">
                                                <option value="">Semua</option>
                                                @foreach ($roles as $jabatan)
                                                    <option value="{{ $jabatan->value }}">{{ $jabatan->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if ($exportErrorType > 0)
                                        <div role="alert" class="text-sm alert alert-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>{{
                                                match($exportErrorType) {
                                                    1 => 'Pilihan tidak boleh kosong!',
                                                    2 => 'Pilihan tidak valid!',
                                                    3 => 'Pilihan range melebihi batas yang dibutuhkan',
                                                    default => ''
                                                }
                                            }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-action">
                                    <button class="btn btn-primary" wire:click="exportXls"
                                        @disabled($exportErrorType > 0)>
                                        Export
                                    </button>
                                    <form method="dialog">
                                        <button class="btn">Close</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </div>
                </div>

                <table class="table w-full h-full border border-gray-300 table-zebra">
                    <thead>
                        <tr class="text-gray-700 bg-gray-200">
                            <th rowspan="2" class="border border-gray-300">No</th>
                            <th rowspan="2" class="border border-gray-300">Nama</th>
                            <th rowspan="2" class="border border-gray-300">Jabatan</th>
                            <th colspan="31" class="border border-gray-300">{{ "$currentMonthName $selectedYear" }}</th>
                        </tr>
                        <tr class="text-gray-600 bg-gray-100">
                            @foreach ($days as $day)
                                <th class="p-1 text-center border border-gray-300 {{ $day['is_weekend'] ? 'bg-error text-zinc-50' : '' }}">{{ $day['day_number'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workers as $karyawan)
                            <tr wire:key="karyawan-{{ $karyawan['id'] }}">
                                <td class="p-2 border border-gray-300">{{ $loop->iteration + $startNumber }}</td>
                                <td class="p-2 border border-gray-300">{{ $karyawan['nama'] }}</td>
                                <td class="p-2 border border-gray-300">{{ $karyawan['jabatan']->name }}</td>
                                @php
        $previousValue = 0;
                                @endphp
                                @foreach ($karyawan['absensi'] as $absensi)
                                    @foreach ($days as $day)

                                        @continue($previousValue >= $day['day_number'])
                                        @break((int) $now->format('j') < $day['day_number'] && (int) $now->month <= $selectedMonth)

                                        @php
                $isDateMatch = (int) $absensi['tanggal'] === $day['day_number'];
                $isClockInOnTime = $absensi['absen_masuk_status'] === 'tepatWaktu';
                $isClockOutOnTime = $absensi['absen_keluar_status'] === 'tepatWaktu';
                $isOnTime = $isClockInOnTime && $isClockOutOnTime && $isDateMatch;

                $isClockInLate = ($absensi['absen_masuk_status'] === 'terlambat') && $isDateMatch;
                $isLeaveEarly = ($absensi['absen_keluar_status'] === 'lebihAwal') && $isDateMatch;

                $noClockIn = !isset($absensi['absen_masuk_status']) && $isDateMatch;
                $noClockOut = !isset($absensi['absen_keluar_status']) && $isDateMatch;
                $isWeekend = $day['is_weekend'];
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
                    'a' => 'text-zinc-50 bg-error',
                    'tp' => 'bg-warning',
                    'v' => 'bg-success',
                    'tl' => 'bg-warning',
                    'tm' => 'bg-warning',
                    'pw' => 'bg-warning',
                    default => ''
                }
                                        @endphp

                                        @if ($loop->parent->last)
                                            @php
                    $previousValue = 0;
                                            @endphp
                                        @endif

                                        <td class="p-0 m-0 text-center border-gray-300 {{ $statusStyling }}">
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
            @else
                <div class="flex items-center justify-center w-full h-full">
                    <span>
                        Data {{ "$currentMonthName $year" }} tidak ditemukan
                    </span>
                </div>
            @endif
        </div>
        {{ $workers->links() }}
    </div>
@endsection
