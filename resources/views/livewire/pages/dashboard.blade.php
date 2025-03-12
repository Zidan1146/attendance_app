@extends('livewire.layout.app')

@section('content')
    <div class="flex justify-between w-full mb-4">
        <h1 class="text-2xl">Dashboard</h1>
        <div class="flex flex-col text-2xl text-right">
            <span>
                {{ $dateNow }}
            </span>
            <div x-data="{ time: '{{ now()->translatedFormat('H:i:s') }}' }"
                x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID'), 1000)">
                <span x-text="time"></span>
            </div>
        </div>
    </div>

    <div class="flex justify-center w-full">
        <div class="flex flex-col justify-center w-4/5 gap-y-3">
            <div class="shadow stats">
                <div class="stat bg-success">
                    <div class="stat-title">Masuk Tepat Waktu</div>
                    <div class="stat-value">{{ $clockInCount }}</div>
                    <div class="stat-desc">{{ $clockInCount }} dari {{ $userCount }} orang</div>
                </div>
                <div class="bg-yellow-200 stat">
                    <div class="stat-title">Terlambat Masuk</div>
                    <div class="stat-value">{{ $lateCount }}</div>
                    <div class="stat-desc">{{ $lateCount }} dari {{ $userCount }} orang</div>
                </div>
                <div class="stat bg-success">
                    <div class="stat-title">Keluar Tepat Waktu</div>
                    <div class="stat-value">{{ $clockOutCount }}</div>
                    <div class="stat-desc">{{ $clockOutCount }} dari {{ $userCount }} orang</div>
                </div>
                <div class="stat bg-warning">
                    <div class="stat-title">Pulang Lebih Awal</div>
                    <div class="stat-value">{{ $earlyClockOut }}</div>
                    <div class="stat-desc">{{ $earlyClockOut }} dari {{ $userCount }} orang</div>
                </div>
                <div class="stat bg-error">
                    <div class="stat-title">Tidak Absen</div>
                    <div class="stat-value">{{ $absentCount }}</div>
                    <div class="stat-desc">{{ $absentCount }} dari {{ $userCount }} orang</div>
                </div>
            </div>
            <div class="flex justify-around gap-8">
                <div class="w-1/2 p-2 border rounded border-primary bg-neutral">
                    <h2>Presensi per bulan</h2>
                    <div class="w-full h-full">
                        <canvas id="attendanceChart" height="256px"></canvas>

                        @script
                            <script>
                                document.addEventListener('livewire:navigated', function () {
                                    const ctx = document.getElementById('attendanceChart').getContext('2d');
                                    let chart;

                                    function updateChart(data) {
                                        if (chart) chart.destroy();

                                        const months = Object.keys(data);
                                        const categories = @json($categories);

                                        // Extract dataset values
                                        const datasets = categories.map(category => ({
                                            label: category,
                                            data: months.map(month => data[month][category] || 0),
                                            borderWidth: 1
                                        }));

                                        chart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: months,
                                                datasets: datasets
                                            },
                                            options: {
                                                responsive: true,
                                                scales: {
                                                    x: { stacked: true },
                                                    y: { stacked: true }
                                                }
                                            }
                                        });
                                    }

                                    Livewire.hook('element.init', ({ component, el }) => {
                                        updateChart(@json($attendanceData));
                                    })
                                    updateChart(@json($attendanceData));
                                });
                            </script>
                        @endscript
                    </div>
                </div>
                <div class="w-1/2 p-2 border rounded border-primary bg-neutral">
                    <div class="flex justify-between">
                        <div class="flex flex-col w-2/3 gap-2">
                            <h2>Presensi per hari ini</h2>
                            <a href="" class="w-fit btn btn-sm btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                    <path
                                        d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                    <path
                                        d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                </svg>
                                <span>Download</span>
                            </a>
                        </div>
                        <div class="flex items-center gap-2 input input-sm input-bordered bg-neutral">
                            <input type="text" name="" id="" placeholder="Search" class="grow" wire:model.live.debounce.1000ms="searchTerm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
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
                                @foreach ($todayData as $absensi)
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
                        {{ $todayData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

