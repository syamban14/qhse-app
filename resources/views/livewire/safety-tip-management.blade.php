<div>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Safety Tips Management</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">A list of all the safety tips in the system.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <button wire:click="create()" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-qhse-accent px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-qhse-accent focus:ring-offset-2 sm:w-auto">
                    Add Safety Tip
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-100 border-l-4 border-qhse-secondary text-emerald-700 p-4 my-4" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        @if($isOpen)
            <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form>
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                        {{ $tip_id ? 'Edit' : 'Create' }} Safety Tip
                                    </h3>
                                    <div class="mt-4">
                                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                        <input type="text" wire:model.lazy="title" id="title" class="mt-1 focus:ring-qhse-primary focus:border-qhse-primary block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        @error('title') <span class="text-qhse-danger text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                                        <textarea wire:model.lazy="content" id="content" rows="4" class="mt-1 focus:ring-qhse-primary focus:border-qhse-primary block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"></textarea>
                                        @error('content') <span class="text-qhse-danger text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="is_active" class="flex items-center">
                                            <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-qhse-primary shadow-sm focus:border-qhse-primary focus:ring focus:ring-qhse-primary focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Active</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-qhse-accent text-base font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-qhse-accent sm:ml-3 sm:w-auto sm:text-sm">
                                    Save
                                </button>
                                <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-500 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Title</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Content</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                                @forelse($tips as $tip)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">{{ $tip->title ?: '-' }}</td>
                                        <td class="whitespace-pre-wrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ Str::limit($tip->content, 80) }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            @if($tip->is_active)
                                                <span class="inline-flex rounded-full bg-emerald-100 px-2 text-xs font-semibold leading-5 text-emerald-800">Active</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-qhse-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <button wire:click="edit({{ $tip->id }})" class="text-qhse-primary hover:text-blue-800">Edit</button>
                                            <button wire:click="delete({{ $tip->id }})" onclick="confirm('Are you sure you want to delete this tip?') || event.stopImmediatePropagation()" class="text-qhse-danger hover:text-red-700 ml-4">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No safety tips found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tips->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>