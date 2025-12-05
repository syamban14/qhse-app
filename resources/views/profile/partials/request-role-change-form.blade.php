<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Permintaan Ganti Role') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Anda dapat mengirim permintaan kepada admin untuk mengubah role Anda.') }}
        </p>
    </header>

    <div class="mt-6">
        <p class="block font-medium text-sm text-gray-700">
            {{ __('Role Anda saat ini: ') }}
            <span class="font-bold">{{ $user->getRoleNames()->implode(', ') }}</span>
        </p>
    </div>

    <form method="post" action="{{ route('profile.request-role') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="role" :value="__('Minta Role Baru')" />
            <x-select-input id="role" name="role" class="mt-1 block w-full">
                <option value="">-- Pilih Role --</option>
                @foreach ($roles as $roleName)
                    <option value="{{ $roleName }}">{{ $roleName }}</option>
                @endforeach
            </x-select-input>
            <x-input-error class="mt-2" :messages="$errors->get('role')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Kirim Permintaan') }}</x-primary-button>

            @if (session('status') === 'role-request-sent')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Permintaan terkirim.') }}</p>
            @endif
        </div>
    </form>
</section>
