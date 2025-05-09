<div class="shadow stats">
    <div class="border-black stat bg-neutral">
        <div class="stat-title">Masuk Tepat Waktu</div>
        <div class="stat-value">{{ $clockInCount }}</div>
        <div class="stat-desc">{{ $clockInCount }} dari {{ $isAdmin ? $userCount : $userClockInCount }} {{ $isAdmin ? 'orang' : 'presensi masuk' }}</div>
    </div>
    <div class="border-black stat bg-neutral">
        <div class="stat-title">Terlambat Masuk</div>
        <div class="stat-value">{{ $lateCount }}</div>
        <div class="stat-desc">{{ $lateCount }} dari {{ $isAdmin ? $userCount : $userClockInCount }} {{ $isAdmin ? 'orang' : 'presensi masuk' }}</div>
    </div>
    <div class="border-black stat bg-neutral">
        <div class="stat-title">Keluar Tepat Waktu</div>
        <div class="stat-value">{{ $clockOutCount }}</div>
        <div class="stat-desc">{{ $clockOutCount }} dari {{ $isAdmin ? $userCount : $userClockOutCount }} {{ $isAdmin ? 'orang' : 'presensi keluar' }}</div>
    </div>
    <div class="border-black stat bg-neutral">
        <div class="stat-title">Pulang Lebih Awal</div>
        <div class="stat-value">{{ $earlyClockOut }}</div>
        <div class="stat-desc">{{ $earlyClockOut }} dari {{ $isAdmin ? $userCount : $userClockOutCount }} {{ $isAdmin ? 'orang' : 'presensi keluar' }}</div>
    </div>
    @if ($isAdmin)
        <div class="border-black stat bg-neutral">
            <div class="stat-title">Tidak Absen</div>
            <div class="stat-value">{{ $absentCount }}</div>
            <div class="stat-desc">{{ $absentCount }} dari {{ $userCount }} orang</div>
        </div>
    @endif
</div>
