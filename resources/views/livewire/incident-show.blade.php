<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Detail Insiden #{{ $incident->id }}
                </h2>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tipe Insiden:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($incident->incident_type) }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Lokasi:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $incident->location }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Deskripsi:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $incident->description }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Waktu Insiden:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $incident->incident_time->format('d M Y H:i') }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pelapor:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $incident->reporter->name }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Status:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($incident->status) }}</p>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('incidents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-2">
                        Kembali
                    </a>
                    <a href="{{ route('incidents.edit', $incident) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Edit Insiden
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>