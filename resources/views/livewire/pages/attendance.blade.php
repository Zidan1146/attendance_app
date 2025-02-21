@extends('livewire.layout.app')

@section('content')
    <h1 class="text-2xl">Data Absen</h1>

    <div class="container flex flex-col items-center justify-center">
        <div class="w-2/3 grid items-center justify-center grid-cols-[3fr_1fr] gap-4 my-4">
            <form action="" method="GET">
                <div class="flex w-full">
                    <label class="flex items-center w-full gap-2 input input-bordered bg-neutral">
                        <input type="text" name="" id="" placeholder="Search" class="grow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </label>
                </div>
            </form>
            <button class="btn btn-primary">Buat Laporan</button>
        </div>
        <div class="flex w-2/3 gap-4">
            <label class="w-1/3">
                <div class="label">
                    <span class="label-text">Dari Tanggal</span>
                </div>
                <select name="" id="" class="w-full select bg-neutral select-bordered">
                    <option value="">1 Jan 2025</option>
                </select>
            </label>
            <label class="w-1/3">
                <div class="label">
                    <span class="label-text">Sampai Tanggal</span>
                </div>
                <select name="" id="" class="w-full select bg-neutral select-bordered">
                    <option value="">5 Jan 2025</option>
                </select>
            </label>
            <label class="w-1/3">
                <div class="label">
                    <span class="label-text">Jabatan</span>
                </div>
                <select name="" id="" class="w-full select bg-neutral select-bordered">
                    <option value="">Semua</option>
                    <option value="">IT</option>
                    <option value="">HR</option>
                    <option value="">Animator</option>
                </select>
            </label>
        </div>

        <div class="flex flex-col w-2/3 gap-6 overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <th></th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jenis</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <td>11</td>
                    <td>John Doe</td>
                    <td>1 Januari 2025</td>
                    <td>08:08</td>
                    <td>Masuk</td>
                    <td>Tepat Waktu</td>
                </tbody>
            </table>
            <div class="join">
                <button class="join-item btn btn-primary">1</button>
                <button class="join-item btn btn-primary btn-active">2</button>
                <button class="join-item btn btn-primary">3</button>
                <button class="join-item btn btn-primary">4</button>
            </div>
        </div>
    </div>
@endsection
