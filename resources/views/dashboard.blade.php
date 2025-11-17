<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-2 border-gray-300 rounded-lg">

                    <!-- Chart and Legend -->
                    <div class="flex flex-col items-center py-12">
                        <div style="width: 400px; height: 400px;" class="mb-4">
                            <canvas id="myPieChart"></canvas>
                        </div>
                        <div class="flex space-x-8">
                            @foreach ($incidentByStatusLabels as $index => $label)
                                <div class="px-6 py-2 rounded-lg font-bold text-white" style="background-color: {{ $incidentByStatusColors[$index] ?? 'rgb(156, 163, 175)' }};">
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Chart.register(ChartDataLabels);

            const ctx = document.getElementById('myPieChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: @json($incidentByStatusLabels),
                        datasets: [{
                            data: @json($incidentByStatusData),
                            backgroundColor: @json($incidentByStatusColors),
                            borderColor: [
                                'rgb(0, 0, 0)',
                                'rgb(0, 0, 0)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            },
                            datalabels: {
                                formatter: (value, ctx) => {
                                    return value;
                                },
                                color: '#fff',
                                font: {
                                    weight: 'bold',
                                    size: 16,
                                },
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
