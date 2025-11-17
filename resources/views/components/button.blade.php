@props([
    'type' => 'primary',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';

    $colorClasses = [
        'primary' => 'bg-qhse-primary hover:bg-qhse-primary/90 focus:bg-qhse-primary/90 active:bg-qhse-primary focus:ring-qhse-primary',
        'secondary' => 'bg-qhse-secondary hover:bg-qhse-secondary/90 focus:bg-qhse-secondary/90 active:bg-qhse-secondary focus:ring-qhse-secondary',
        'danger' => 'bg-qhse-danger hover:bg-qhse-danger/90 focus:bg-qhse-danger/90 active:bg-qhse-danger focus:ring-qhse-danger',
    ];

    $classes = $baseClasses . ' ' . ($colorClasses[$type] ?? $colorClasses['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
