@props(['title' => 'Dashboard', 'subtitle' => ''])

<div class="bg-neutral-0 border-b border-neutral-200 px-6 py-4 flex items-center justify-between">

    {{-- PAGE TITLE --}}
    <div class="flex items-center gap-4">
        <button class="text-neutral-700 text-xl">
            <img src="{{ asset('images/icons/grey-menu.svg') }}" class="w-5 h-5" alt="Menu">
        </button>
        <div>
            <h1 class="font-heading font-bold text-xl text-neutral-900">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-sm text-neutral-600">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    {{-- USER INFO --}}
    <div class="flex items-center gap-5">

        {{-- DATE TIME --}}
        <span class="text-sm text-neutral-600">
            {{ now()->format('M d, Y  h:i A') }}
        </span>

        {{-- PROFILE --}}
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-full bg-neutral-200 flex items-center justify-center font-semibold text-neutral-700">
                {{ substr(auth()->user()?->first_name ?? 'U', 0, 1) }}
            </div>
            <div class="text-sm">
                <p class="font-semibold text-neutral-900">{{ auth()->user()?->first_name ?? 'Guest' }}</p>
                <p class="text-xs text-neutral-600">{{ ucfirst(auth()->user()?->role ?? '') }}</p>
            </div>
        </div>

    </div>

</div>