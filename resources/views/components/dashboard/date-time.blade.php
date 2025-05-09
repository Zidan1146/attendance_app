<div class="flex flex-col text-2xl text-right">
    <span>
        {{ $dateNow }}
    </span>
    <div x-data="{ time: '{{ now()->translatedFormat('H.i.s') }}' }"
        x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID'), 1000)">
        <span x-text="time"></span>
    </div>
</div>
