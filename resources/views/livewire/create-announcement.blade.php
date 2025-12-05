<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Pengumuman Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-qhse-neutral-dark shadow sm:rounded-lg">
                <form wire:submit.prevent="sendAnnouncement">
                    <div class="max-w-full">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Detail Pengumuman
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Pesan ini akan dikirim ke inbox semua pengguna aktif di dalam sistem.
                        </p>

                        @if (session()->has('success'))
                            <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="space-y-6 mt-6">
                            {{-- Judul Pengumuman --}}
                            <div>
                                <x-input-label for="title" value="Judul Pengumuman" />
                                <x-text-input type="text" wire:model="title" id="title" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            {{-- Isi Pengumuman --}}
                            <div>
                                <x-input-label for="body" value="Isi Pengumuman" />
                                <textarea wire:model="body" id="body" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-qhse-primary dark:focus:border-qhse-secondary focus:ring-qhse-primary dark:focus:ring-qhse-secondary rounded-md shadow-sm" rows="8"></textarea>
                                <x-input-error :messages="$errors->get('body')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <x-primary-button>
                                <div wire:loading.remove wire:target="sendAnnouncement">
                                    Kirim Pengumuman
                                </div>
                                <div wire:loading wire:target="sendAnnouncement">
                                    Mengirim...
                                </div>
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>