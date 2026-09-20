@props(['label' => null, 'name', 'type' => 'text', 'placeholder' => null, 'value' => null])

<div>
    {{-- LABEL --}}
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-neutral-900 mb-1 font-body">
            {{ $label }}
        </label>
    @endif

    <div class="relative">

        {{-- ICON (OPTIONAL) --}}
        @isset($icon)
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-600">
                {{ $icon }}
            </div>
        @endisset

        {{-- INPUT FIELD --}}
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => 'w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 ' . (isset($icon) ? 'pl-10' : 'pl-4') . ' pr-4 text-sm font-body text-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-yellow']) }}
        >

    </div>
</div>