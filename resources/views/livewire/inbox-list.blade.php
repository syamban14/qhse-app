<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kotak Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Filter Tabs and Bulk Actions -->
                    <div class="mb-4 pb-2 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
                        <div class="flex space-x-4">
                            <button wire:click.prevent="setFilter('all')" 
                                    class="py-2 px-4 text-sm font-medium focus:outline-none {{ $filter === 'all' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Semua
                            </button>
                            <button wire:click.prevent="setFilter('unread')" 
                                    class="py-2 px-4 text-sm font-medium focus:outline-none {{ $filter === 'unread' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Belum Dibaca
                            </button>
                        </div>

                        @if(count($selectedNotifications) > 0)
                            <div>
                                <button wire:click="deleteSelected" wire:confirm="Anda yakin ingin menghapus notifikasi yang dipilih?" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    Hapus ({{ count($selectedNotifications) }})
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse ($notifications as $notification)
                            <div class="flex items-center space-x-4">
                                <!-- Checkbox -->
                                <div>
                                    <input type="checkbox" wire:model.live="selectedNotifications" value="{{ $notification->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                </div>

                                <!-- Notification Card -->
                                <div wire:click="markAsRead({{ $notification->id }})"
                                     class="flex-grow flex items-start p-4 rounded-lg {{ is_null($notification->read_at) ? 'bg-gray-100 dark:bg-gray-700' : '' }} border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-150">
                                    <div class="flex-shrink-0 mr-4 mt-1">
                                        @if (is_null($notification->read_at))
                                            <span class="flex h-3 w-3">
                                                <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-sky-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                                            </span>
                                        @else
                                            <span class="flex h-3 w-3">
                                                <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-400"></span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-center">
                                            <p class="font-semibold text-lg">{{ $notification->title }}</p>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Dari: {{ $notification->sender->name ?? 'Sistem' }}
                                        </p>
                                        <p class="mt-2">{{ $notification->body }}</p>

                                        @if ($notification->type === 'role_request' && auth()->user()->hasRole('admin'))
                                            <div class="mt-4 flex space-x-2 justify-end">
                                                <button wire:click.stop="approveRoleChange({{ $notification->id }})" class="px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                    {{ __('Setujui') }}
                                                </button>
                                                <button wire:click.stop="denyRoleChange({{ $notification->id }})" class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                                    {{ __('Tolak') }}
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p>{{ __('Tidak ada pesan di kotak masuk Anda.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($notifications->hasPages())
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>