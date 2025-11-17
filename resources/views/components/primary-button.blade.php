<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-qhse-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-md hover:bg-qhse-primary/90 hover:shadow-lg focus:bg-qhse-primary/90 active:bg-qhse-primary focus:outline-none focus:ring-2 focus:ring-qhse-primary focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
