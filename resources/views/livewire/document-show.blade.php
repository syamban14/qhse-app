<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Detail Dokumen: {{ $document->title }}
                </h2>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Judul:</strong> {{ $document->title }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Kode Dokumen:</strong> {{ $document->document_code }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Versi:</strong> {{ $document->version }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Pengunggah:</strong> {{ $document->uploader->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Tanggal Unggah:</strong> {{ $document->created_at->format('d M Y H:i') }}</p>
                </div>

                <div class="flex items-center justify-start mt-4">
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-2">
                        Kembali ke Daftar Dokumen
                    </a>
                    <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                        Unduh Dokumen
                    </a>
                    @can('edit document')
                        <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Dokumen
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>