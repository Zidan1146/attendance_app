<div class="w-1/2 p-2 border rounded border-primary bg-neutral">
    <div class="flex justify-between w-full">
        <h2>Presensi per bulan</h2>
        <select wire:model.live="selectedYear" class="select select-sm select-bordered">
            @foreach ($years as $year)
                <option value="{{ $year }}" {{ $selectedYear === $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
    </div>
    <div class="w-full h-full">
        <canvas id="attendanceChart" height="256px"></canvas>

        @script
            <script>
                document.addEventListener('livewire:initialized', function () {
                    const ctx = document.getElementById('attendanceChart').getContext('2d');
                    let chart;

                    function updateChart(data) {
                        if (chart) chart.destroy();

                        const months = Object.keys(data);
                        const categories = @json($categories);

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

                    Livewire.on('attendanceUpdated', data => {
                        updateChart(data[0]);
                        console.log(data);
                    });

                    updateChart(@json($attendanceData));
                });
            </script>
        @endscript
    </div>
</div>
