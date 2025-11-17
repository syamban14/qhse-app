<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Detail Audit #{{ $audit->id }}
                </h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Judul:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $audit->title }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Auditor:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $audit->auditor->name ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal Audit:</p>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $audit->schedule_date?->format('d M Y') ?? 'N/A' }}</p>
                </div>

                <!-- Audit Status Update -->
                <div class="mb-6">
                    <label for="auditStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Audit</label>
                    <select wire:model="auditStatus" id="auditStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        <option value="scheduled">Dijadwalkan</option>
                        <option value="in_progress">Dalam Proses</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    @error('auditStatus') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <button wire:click="updateAuditStatus" class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Perbarui Status Audit
                    </button>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mb-4">Item Audit</h3>

                <form wire:submit.prevent="saveAuditItems">
                    @foreach ($auditItems as $itemId => $itemData)
                        <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <p class="font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $loop->iteration }}. {{ $itemData['item_question'] }}</p>

                            <!-- Result -->
                            <div class="mb-2">
                                <label for="result-{{ $itemId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasil</label>
                                <select wire:model="auditItems.{{ $itemId }}.result" id="result-{{ $itemId }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">-- Pilih Hasil --</option>
                                    <option value="compliant">Sesuai</option>
                                    <option value="non_compliant">Tidak Sesuai</option>
                                    <option value="n_a">Tidak Berlaku</option>
                                </select>
                                @error('auditItems.' . $itemId . '.result') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Remarks -->
                            <div class="mb-2">
                                <label for="remarks-{{ $itemId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                                <textarea wire:model="auditItems.{{ $itemId }}.remarks" id="remarks-{{ $itemId }}" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                                @error('auditItems.' . $itemId . '.remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Simpan Item Audit
                        </button>
                    </div>
                </form>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('audits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-2">
                        Kembali ke Daftar Audit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>