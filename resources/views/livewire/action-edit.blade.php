<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Edit Tindakan #{{ $action->id }}
                </h2>

                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <!-- Incident Link (Optional) -->
                    <div class="mb-4">
                        <label for="incident_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Terkait Insiden (Opsional)</label>
                        <select wire:model="incident_id" id="incident_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="">-- Pilih Insiden --</option>
                            @foreach ($incidents as $incident)
                                <option value="{{ $incident->id }}">{{ $incident->id }} - {{ Str::limit($incident->description, 50) }}</option>
                            @endforeach
                        </select>
                        @error('incident_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Asset/Location ID -->
                    <div class="mb-4">
                        <label for="asset_or_location_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aset/Lokasi Terkait (Opsional, cth: Nopol B 1234 XYZ, Gudang Rak 3C)</label>
                        <input type="text" wire:model="asset_or_location_id" id="asset_or_location_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('asset_or_location_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi Tindakan</label>
                        <textarea wire:model="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- PIC User -->
                    <div class="mb-4">
                        <label for="pic_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penanggung Jawab (PIC)</label>
                        <select wire:model="pic_user_id" id="pic_user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="">-- Pilih PIC --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('pic_user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Due Date -->
                    <div class="mb-4">
                        <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batas Waktu</label>
                        <input type="date" wire:model="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="open">Terbuka</option>
                            <option value="in_progress">Dalam Proses</option>
                            <option value="completed">Selesai</option>
                            <option value="pending_verification">Menunggu Verifikasi</option>
                            <option value="closed_effective">Ditutup (Efektif)</option>
                            <option value="closed_ineffective">Ditutup (Tidak Efektif)</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if(in_array($status, ['completed', 'pending_verification', 'closed_effective', 'closed_ineffective']))
                        <!-- Completion Notes -->
                        <div class="mb-4">
                            <label for="completion_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Penyelesaian</label>
                            <textarea wire:model="completion_notes" id="completion_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                            @error('completion_notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    @if(in_array($status, ['pending_verification', 'closed_effective', 'closed_ineffective']))
                        <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mb-4">Verifikasi Efektivitas</h3>

                        <!-- Effectiveness Verification Notes -->
                        <div class="mb-4">
                            <label for="effectiveness_verification_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Verifikasi</label>
                            <textarea wire:model="effectiveness_verification_notes" id="effectiveness_verification_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                            @error('effectiveness_verification_notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Effectiveness Verification Date -->
                        <div class="mb-4">
                            <label for="effectiveness_verification_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Verifikasi</label>
                            <input type="date" wire:model="effectiveness_verification_date" id="effectiveness_verification_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @error('effectiveness_verification_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('actions.show', $action) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-2">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Perbarui Tindakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>