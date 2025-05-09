@extends('livewire.layout.app')

@section('content')
    @assets
        <script type="module" src="https://unpkg.com/cally"></script>
    @endassets
    <h1 class="text-2xl">Rekap Presensi</h1>

    <div class="flex flex-col justify-center">
        <div class="flex justify-start w-2/3">
            <div class="gap-3 form-control">
                @if ($isAdmin)
                    <div class="flex w-full gap-2">
                        <label class="w-48">
                            <div class="label">
                                <span class="label-text">Jabatan</span>
                            </div>
                            <select name="" id="" wire:model.live="selectedRole" class="w-full select bg-neutral select-bordered">
                                <option value="">Semua</option>
                                @foreach ($roles as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                @endif
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
            <div class="flex justify-between w-full h-full align-bottom">
                <span class="w-3/5 mt-auto text-sm italic text-primary">
                    Keterangan: v = tepat waktu, tl = terlambat masuk, pw = pulang sebelum waktunya, tm = tidak absen masuk, tp = tidak absen pulang, a = tidak absen
                </span>
                @if ($isAdmin)
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
                                <h3
                                    class="text-lg font-bold"
                                    x-data="{ fileType: $wire.entangle('reportFileType') }">
                                    Export sebagai
                                    <select
                                        class="select select-sm bg-neutral"
                                        x-model="fileType">
                                        <option value="xlsx">xlsx</option>
                                        <option value="pdf">pdf</option>
                                    </select>
                                </h3>
                                <div
                                    class="flex flex-col gap-4"
                                    x-data="{
                                        periodType: $wire.entangle('reportPeriodType').live
                                    }">
                                    <div class="flex flex-col">
                                        <label for="" class="label">Jenis Periode</label>
                                        <select wire:ignore.self name="" id="" x-model="periodType" class="join-item select bg-neutral">
                                            <option value="monthly">Bulanan</option>
                                            <option value="daily">Harian</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-between">
                                        <div class="">
                                            <label for="" class="label">Periode</label>
                                            {{-- Shown when periodType === Monthly --}}
                                            <div class="join" x-show="periodType === 'monthly'">
                                                <select name="" id="" class="join-item select bg-neutral" wire:model.live="selectedExportStartMonth" wire:key="start-month">
                                                    @foreach ($months as $monthNumber => $monthName)
                                                        <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                                    @endforeach
                                                </select>
                                                <select name="" id="" class="join-item select bg-neutral" wire:model.live="selectedExportEndMonth" wire:key="end-month">
                                                    @foreach ($months as $monthNumber => $monthName)
                                                        <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- End Monthly --}}

                                            {{-- Shown when periodType === Daily --}}
                                            <div
                                                x-show="periodType === 'daily'"
                                                x-data="{
                                                    calendarToggle: $wire.entangle('isCalendarOpen').live,
                                                    selectedDate: $wire.entangle('selectedExportDate').live
                                                }">
                                                <button
                                                    x-on:click="calendarToggle = !calendarToggle"
                                                    popovertarget="cally-popover1"
                                                    class="input input-border bg-neutral"
                                                    id="cally1"
                                                    style="anchor-name:--cally1">
                                                    {{ $selectedExportDate ? "$selectedStartDate sampai $selectedEndDate" : "Pilih Tanggal" }}
                                                </button>
                                                <div
                                                    popover
                                                    x-show="calendarToggle"
                                                    x-on:click.outside="calendarToggle = false"
                                                    id="cally-popover1"
                                                    class="dropdown bg-neutral rounded-box shadow-lg"
                                                    style="position-anchor:--cally1">
                                                    <calendar-range
                                                        class="cally"
                                                        @change="selectedDate = $event.target.value"
                                                        months="2">
                                                        <svg aria-label="Previous" class="fill-current size-4" slot="previous" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.75 19.5 8.25 12l7.5-7.5"></path></svg>
                                                        <svg aria-label="Next" class="fill-current size-4" slot="next" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="m8.25 4.5 7.5 7.5-7.5 7.5"></path></svg>
                                                        <div class="flex flex-row">
                                                            <calendar-month></calendar-month>
                                                            <calendar-month offset="1"></calendar-month>
                                                        </div>
                                                        <div class="m-4 flex justify-end">
                                                            <button
                                                                class="btn btn-primary px-4"
                                                                x-on:click="calendarToggle = false">
                                                                Confirm
                                                            </button>
                                                        </div>
                                                    </calendar-range>
                                                </div>
                                            </div>
                                            {{-- End Daily --}}
                                        </div>
                                        {{-- Shown when periodType === Monthly --}}
                                        <div x-show="periodType === 'monthly'">
                                            <label for="" class="label">Tahun</label>
                                            <select name="" id="" class="join-item select bg-neutral" wire:model="selectedExportYear">
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}" {{ $year === $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div x-show="periodType === 'monthly'">
                                            <label for="" class="label">Tahun</label>
                                            <select name="" id="" class="join-item select bg-neutral" wire:model="selectedExportYear">
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}" {{ $year === $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- End Monthly --}}
                                        <div x-show="periodType === 'daily'">
                                            <label for="" class="label">Jabatan</label>
                                            <select name="" id="" class="join-item select bg-neutral" wire:model="selectedExportRole">
                                                <option value="">Semua</option>
                                                @foreach ($roles as $jabatan)
                                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="w-full" x-show="periodType === 'monthly'">
                                        <label for="" class="label">Jabatan</label>
                                        <select name="" id="" class="join-item select bg-neutral w-full" wire:model="selectedExportRole">
                                            <option value="">Semua</option>
                                            @foreach ($roles as $jabatan)
                                                <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                            @endforeach
                                        </select>
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

                                    <form wire:submit="export">
                                        <button class="btn btn-primary"
                                            @disabled($exportErrorType > 0)>
                                            <div wire:loading wire:target="export">
                                                <span class="loading loading-spinner loading-md"></span>
                                            </div>

                                            Export
                                        </button>
                                    </form>
                                    <form method="dialog">
                                        <button class="btn" @disabled($isCalendarOpen)>Close</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </div>
                @endif
            </div>

            <table class="table w-full h-full border border-gray-300 table-zebra">
                <thead>
                    <tr class="text-gray-700 bg-gray-200">
                        @if ($isAdmin)
                            <th rowspan="2" class="border border-gray-300">No</th>
                            <th rowspan="2" class="border border-gray-300">Nama</th>
                            <th rowspan="2" class="border border-gray-300">Jabatan</th>
                        @endif
                        <th colspan="31" class="border border-gray-300 text-center">{{ "$currentMonthName $selectedYear" }}</th>
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
                            @if ($isAdmin)
                                <td class="p-2 border border-gray-300">{{ $loop->iteration + $startNumber }}</td>
                                <td class="p-2 border border-gray-300">{{ $karyawan['nama'] }}</td>
                                <td class="p-2 border border-gray-300">{{ $karyawan['jabatan'] }}</td>
                            @endif
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
        </div>
        {{ $workers->links() }}
    </div>
@endsection
