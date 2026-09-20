@props(['active' => ''])

<aside class="w-64 bg-brand-black text-neutral-0 flex flex-col font-heading border-r border-neutral-900">

    {{-- LOGO --}}
    <div class="p-6 flex flex-col items-center border-b border-neutral-900 text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Four Stripes Logo" class="h-20 mb-3">
        <p class="font-bold text-sm tracking-wide">FOUR STRIPES</p>
        <p class="text-[10px] text-brand-yellow tracking-wide">EQUIPMENT AND MACHINES CORP.</p>
    </div>

    {{-- NAV LINKS --}}
    <nav class="flex-1 p-4 space-y-1">

        {{-- POS --}}
        <a href="{{ route('pos.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'pos' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            POS
        </a>

        {{-- INVENTORY --}}
        <a href="{{ route('inventory.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'inventory' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="3" width="22" height="5" rx="1"></rect>
                <path d="M3 8v11a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8"></path>
                <line x1="10" y1="12" x2="14" y2="12"></line>
            </svg>
            Inventory
        </a>

        {{-- SALES TRACKING --}}
        <a href="{{ route('sales.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'sales' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
            </svg>
            Sales Tracking
        </a>

        {{-- REPORTS --}}
        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'reports' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
            Reports
        </a>

        {{-- ADMIN ONLY LINKS --}}
        @if(auth()->user()?->isAdmin())

            {{-- USER MANAGEMENT --}}
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'users' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                User Management
            </a>

            {{-- AUDIT LOG --}}
            <a href="{{ route('audit-log.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'audit-log' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                </svg>
                Audit Log
            </a>

        @endif

    </nav>

    {{-- FOOTER TAGLINE --}}
    <div class="p-4 text-[10px] text-neutral-600 border-t border-neutral-900 font-body tracking-wide">
        CACAO MACHINES.<br>
        <span class="text-brand-yellow">BETTER POSSIBILITIES.</span>
    </div>

</aside>