<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Insiden Terbuka</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $openIncidentsCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Tindakan Terbuka</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $openActionsCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-red-500">Tindakan Lewat Batas</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-600">{{ $overdueActionsCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Divisi Paling Rawan</h3>
                    @if ($topDivision)
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $topDivision->div_name }}">{{ $topDivision->div_name }}</p>
                        <p class="text-sm text-gray-500">{{ $topDivision->accident_count }} insiden</p>
                    @else
                        <p class="mt-2 text-xl font-semibold text-gray-400">Data tidak tersedia</p>
                    @endif
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Incidents per Month Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Insiden per Bulan (12 Bulan Terakhir)</h3>
                    <canvas id="incidentsByMonthChart"></canvas>
                </div>
                <!-- Actions by Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Status Tindakan</h3>
                    <div class="max-h-80 flex justify-center">
                        <canvas id="actionsByStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- My Pending Actions Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-4">Tindakan Saya yang Tertunda</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi Tindakan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Batas Waktu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Terkait Insiden</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($myPendingActions as $action)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 dark:text-white">{{ $action->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $action->due_date->isPast() ? 'text-red-500 font-bold' : 'text-gray-500 dark:text-gray-300' }}">
                                            {{ $action->due_date->format('d M Y') }}
                                            @if($action->due_date->isPast())
                                                <span class="text-xs">({{ $action->due_date->diffForHumans(null, true) }} lalu)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-500 dark:text-gray-300">
                                            @if($action->car)
                                                {{ $action->car->rootCauseAnalysis->accident->description }}
                                            @elseif($action->incident)
                                                {{ $action->incident->description }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($action->car)
                                                <a href="{{ route('cars.show', $action->car) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Lihat CAR</a>
                                            @elseif($action->incident)
                                                <a href="{{ route('accidents.show', $action->incident_id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Lihat Insiden</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Anda tidak memiliki tindakan yang tertunda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Incidents by Month Chart
            const incidentsCtx = document.getElementById('incidentsByMonthChart');
            if (incidentsCtx) {
                new Chart(incidentsCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($incidentByMonthLabels),
                        datasets: [{
                            label: 'Jumlah Insiden',
                            data: @json($incidentByMonthData),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Actions by Status Chart
            const actionsCtx = document.getElementById('actionsByStatusChart');
            if (actionsCtx) {
                new Chart(actionsCtx, {
                    type: 'pie',
                    data: {
                        labels: @json($actionByStatusLabels),
                        datasets: [{
                            label: 'Status Tindakan',
                            data: @json($actionByStatusData),
                            backgroundColor: [
                                'rgba(255, 159, 64, 0.5)', // Open
                                'rgba(75, 192, 192, 0.5)', // Closed
                                'rgba(153, 102, 255, 0.5)', // Continued
                            ],
                            borderColor: [
                                'rgba(255, 159, 64, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
