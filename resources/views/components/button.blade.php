@props(['type' => 'submit', 'variant' => 'primary'])

@php
    $base = 'font-heading font-bold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2';
    $variants = [
        'primary' => 'bg-brand-yellow text-brand-black hover:opacity-90',
        'secondary' => 'bg-neutral-200 text-neutral-900 hover:bg-neutral-100',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base . ' ' . $variants[$variant]]) }}>
    {{ $slot }}
</button>