<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Edit Dokumen: {{ $document->title }}
                </h2>

                <form wire:submit.prevent="save">
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="document.title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Dokumen</label>
                        <input type="text" wire:model="document.title" id="document.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('document.title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Document Type -->
                    <div class="mb-4">
                        <label for="document.document_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Dokumen</label>
                        <input type="text" wire:model="document.document_type" id="document.document_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('document.document_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Version -->
                    <div class="mb-4">
                        <label for="document.version" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Versi</label>
                        <input type="text" wire:model="document.version" id="document.version" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('document.version') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Expiry Date -->
                    <div class="mb-4">
                        <label for="document.expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Kedaluwarsa (Opsional)</label>
                        <input type="date" wire:model="document.expiry_date" id="document.expiry_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('document.expiry_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Current File (Display only) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">File Saat Ini</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <a href="{{ route('documents.download', $document) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600">
                                {{ basename($document->file_path) }}
                            </a>
                        </p>
                    </div>

                    <!-- New File Upload (Optional) -->
                    <div class="mb-4">
                        <label for="new_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unggah Versi Baru (Opsional)</label>
                        <input type="file" wire:model="new_file" id="new_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600">
                        @error('new_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="new_file" class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mengunggah...</div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('documents.show', $document) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-2">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Perbarui Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>