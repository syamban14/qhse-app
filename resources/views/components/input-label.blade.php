@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-qhse-neutral-dark dark:text-qhse-neutral-light']) }}>
    {{ $value ?? $slot }}
</label>
