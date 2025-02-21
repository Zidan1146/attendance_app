@extends('livewire.layout.app')

@section('content')
    <div class="flex justify-between w-full mb-4">
        <h1 class="text-2xl">Dashboard</h1>
        <div class="flex flex-col text-2xl text-right">
            <span>
                Selasa, 2 Januari 2025
            </span>
            <span>
                10:00:21
            </span>
        </div>
    </div>

    <div class="flex justify-center w-full">
        <div class="flex flex-col justify-center w-2/3 gap-y-3">
            <div class="shadow stats">
                <div class="stat bg-success">
                    <div class="stat-figure">
                        <div class="stat-title">Presensi Tepat Waktu</div>
                        <div class="stat-value">118</div>
                        <div class="stat-desc">118 dari 178 orang</div>
                    </div>
                </div>
                <div class="bg-yellow-200 stat">
                    <div class="stat-figure">
                        <div class="stat-title">Terlambat Masuk</div>
                        <div class="stat-value">30</div>
                        <div class="stat-desc">30 dari 178 orang</div>
                    </div>
                </div>
                <div class="stat bg-warning">
                    <div class="stat-figure">
                        <div class="stat-title">Pulang Lebih Awal</div>
                        <div class="stat-value">16</div>
                        <div class="stat-desc">16 dari 178 orang</div>
                    </div>
                </div>
                <div class="stat bg-error">
                    <div class="stat-figure">
                        <div class="stat-title">Tidak Absen</div>
                        <div class="stat-value">38</div>
                        <div class="stat-desc">38 dari 178 orang</div>
                    </div>
                </div>
            </div>
            <div class="flex justify-around gap-8">
                <div class="w-1/2 p-2 border rounded border-primary bg-neutral">
                    <h2>Presensi per bulan</h2>
                    <div class="w-full h-full">
                        <canvas id="attendanceChart" height="256px"></canvas>
                    </div>
                </div>
                <div class="w-1/2 p-2 border rounded border-primary bg-neutral">
                    <div class="flex justify-between">
                        <div class="flex flex-col w-2/3 gap-2">
                            <h2>Presensi per hari ini</h2>
                            <a href="" class="w-3/4 btn btn-sm btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                    <path
                                        d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                    <path
                                        d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                </svg>
                                <span>Download</span>
                            </a>
                        </div>
                        <form action="GET">
                            <label class="flex items-center gap-2 input input-sm input-bordered bg-neutral">
                                <input type="text" name="" id="" placeholder="Search" class="grow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </label>
                        </form>
                    </div>
                    <div class="flex flex-col w-full gap-6 overflow-x-auto">
                        <table class="table w-full text-xs">
                            <thead>
                                <th></th>
                                <th>Nama</th>
                                <th>Jam</th>
                                <th>Jenis</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>11</td>
                                    <td>John Doe</td>
                                    <td>08:08</td>
                                    <td>Masuk</td>
                                    <td>Tepat Waktu</td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>John Doe</td>
                                    <td>08:08</td>
                                    <td>Masuk</td>
                                    <td>Tepat Waktu</td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>John Doe</td>
                                    <td>08:08</td>
                                    <td>Masuk</td>
                                    <td>Tepat Waktu</td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>John Doe</td>
                                    <td>08:08</td>
                                    <td>Masuk</td>
                                    <td>Tepat Waktu</td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>John Doe</td>
                                    <td>08:08</td>
                                    <td>Masuk</td>
                                    <td>Tepat Waktu</td>
                                </tr>
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
            </div>
        </div>
    </div>
@endsection

