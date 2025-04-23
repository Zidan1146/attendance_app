@extends('livewire.layout.app')

@section('content')
    <h1 class="text-2xl">Data Absen</h1>

    <div class="container flex flex-col items-center justify-center">
        <div class="w-full gap-4 my-4">
            <div class="flex w-full">
                <label class="flex items-center w-full gap-2 input input-bordered bg-neutral">
                    <input type="text" name="" id="" placeholder="Search" class="grow" wire:model.live.debounce.1000ms="searchTerm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </label>
            </div>
        </div>
        <div class="flex justify-between w-full gap-4 mb-3">
            <div class="flex items-end">
                <div class="">
                    <label class="label">
                        <span class="label-text">Tanggal</span>
                    </label>
                    <div class="join join-horizontal">
                        <select name="" id="" class="w-full join-item select bg-neutral select-bordered" wire:model.live="startDate">
                            <option value="">Semua tanggal</option>
                            <option value="" disabled>-- Dari Tanggal --</option>
                            @foreach ($availableDates as $date)
                                <option value="{{ $date }}">{{ $date->translatedFormat('j F Y') }}</option>
                            @endforeach
                        </select>
                        <select name="" id="" class="w-full select join-item bg-neutral select-bordered" wire:model.live="endDate">
                            <option value="">Semua tanggal</option>
                            <option value="" disabled>-- Sampai Tanggal --</option>
                            @foreach ($availableDates as $date)
                                <option value="{{ $date }}">{{ $date->translatedFormat('j F Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="">
                    <div class="label">
                        <span class="label-text">Filter</span>
                    </div>
                    <div class="join">
                        <select name="" id="" class="select bg-neutral select-bordered join-item" wire:model.live="selectedRole">
                            <option value="">Semua Jabatan</option>
                            <option value="-1">Tanpa Jabatan</option>
                            @foreach ($roles as $jabatan)
                                <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                            @endforeach
                        </select>
                        <select name="" id="" class="select bg-neutral select-bordered join-item" wire:model.live="selectedAttendanceType">
                            <option value="">Semua Jenis Absen</option>
                            @foreach ($attendaceTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <select name="" id="" class="select bg-neutral select-bordered join-item" wire:model.live="selectedAttendanceStatus">
                            <option value="">Semua Status Absen</option>
                            @foreach ($attendanceStatuses as $status)
                                <option value="{{ $status->value }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col w-full gap-6 overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <th></th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jenis</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    @foreach ($dataAbsensi as $absensi)
                        @php
                            $jenis = match ($absensi->jenisAbsen) {
                                    $jenisAbsenEnum::AbsenMasuk => 'Masuk',
                                    $jenisAbsenEnum::AbsenKeluar => 'Keluar',
                                    $jenisAbsenEnum::Lembur => $jenisAbsenEnum::Lembur,
                                default => '',
                            };
                            $statusStyling = match ($absensi->status) {
                                    $statusAbsenEnum::TidakDiketahui => 'bg-error text-zinc-50',
                                    $statusAbsenEnum::TepatWaktu => 'bg-success',
                                    $statusAbsenEnum::Terlambat => 'bg-error text-zinc-50',
                                    $statusAbsenEnum::LebihAwal => 'bg-warning',
                                    $statusAbsenEnum::TidakAbsen => 'bg-error text-zinc-50',
                                default => ''
                            }
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + $startNumber }}</td>
                            <td>{{ $absensi->karyawan->nama }}</td>
                            <td>{{ $absensi->karyawan->jabatan->nama ?? 'Tanpa Jabatan' }}</td>
                            <td>{{ $absensi->tanggal->translatedFormat('j F Y') }}</td>
                            <td>{{ $absensi->waktu }}</td>
                            <td>{{ $jenis }}</td>
                            <td>
                                <span class="{{ $statusStyling }} py-1 px-2 rounded-xl">
                                    {{ preg_replace('/([a-z])([A-Z])/', '$1 $2', ucfirst($absensi->status->value)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $dataAbsensi->links() }}
        </div>
    </div>
@endsection
