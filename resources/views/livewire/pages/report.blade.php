@extends('livewire.layout.app')

@section('content')
    <h1 class="text-2xl">Rekap Presensi</h1>

    <div class="flex flex-col justify-center gap-5">
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
                        <span class="label-text">Periode Bulan</span>
                    </label>
                    <div class="join join-horizontal">
                        <select name="" id="" class="w-full select bg-neutral select-bordered join-item" wire:model.live="selectedMonth">
                            @foreach ($months as $monthNumber => $monthName)
                                <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                            @endforeach
                        </select>
                        <select name="" id="" class="w-40 select bg-neutral select-bordered join-item">
                            <option value="">{{ $year }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <span class="text-sm italic text-primary">
                Keterangan: v = tepat waktu, tl = terlambat masuk, pw = pulang sebelum waktunya, tm = tidak absen masuk, tp = tidak absen pulang, a = tidak absen
            </span>
            <table class="table w-full border border-gray-300 table-zebra">
                <thead>
                    <tr class="text-gray-700 bg-gray-200">
                        <th rowspan="2" class="border border-gray-300">No</th>
                        <th rowspan="2" class="border border-gray-300">Nama</th>
                        <th rowspan="2" class="border border-gray-300">Jabatan</th>
                        <th colspan="31" class="border border-gray-300">{{ "$currentMonthName $year" }}</th>
                    </tr>
                    <tr class="text-gray-600 bg-gray-100">
                        @foreach ($days as $day)
                            <th class="p-1 text-center border border-gray-300 {{ $day['is_weekend'] ? 'bg-error text-zinc-50' : '' }}">{{ $day['day_number'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($workers as $karyawan)
                        <tr>
                            <td class="p-2 border border-gray-300">{{ $loop->iteration }}</td>
                            <td class="p-2 border border-gray-300">{{ $karyawan->nama }}</td>
                            <td class="p-2 border border-gray-300">{{ $karyawan->jabatan->name }}</td>
                            <td class="p-0 text-center text-white bg-green-500 border border-gray-300">v</td>
                            <td class="p-0 text-center text-black bg-pink-300 border border-gray-300">tl</td>
                            <td class="p-0 text-center text-black bg-blue-300 border border-gray-300">pw</td>
                            <td class="p-0 text-center text-white bg-green-500 border border-gray-300">v</td>
                            <td class="p-0 text-center text-black bg-pink-300 border border-gray-300">tp</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $workers->links() }}
    </div>
@endsection
