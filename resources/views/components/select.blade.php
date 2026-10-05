@props(['label' => null, 'name', 'options' => [], 'value' => null])

<div>
    {{-- LABEL --}}
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-neutral-900 mb-1 font-body">
            {{ $label }}
        </label>
    @endif

    {{-- SELECT FIELD --}}
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body text-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-yellow']) }}
    >
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
