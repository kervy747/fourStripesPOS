@props(['name'])

{{-- VALIDATION ERROR --}}
@error($name)
    <p class="text-danger text-xs mt-1 font-body">{{ $message }}</p>
@enderror