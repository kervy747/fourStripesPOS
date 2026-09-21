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
            <img src="{{ asset('images/icons/' . ($active === 'pos' ? 'black-cart.svg' : 'white-cart.svg')) }}" class="w-5 h-5" alt="">
            POS
        </a>

        {{-- INVENTORY --}}
        <a href="{{ route('inventory.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'inventory' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
            <img src="{{ asset('images/icons/' . ($active === 'inventory' ? 'black-inventory.svg' : 'white-inventory.svg')) }}" class="w-5 h-5" alt="">
            Inventory
        </a>

        {{-- SALES TRACKING --}}
        <a href="{{ route('sales.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'sales' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
            <img src="{{ asset('images/icons/' . ($active === 'sales' ? 'black-sales.svg' : 'white-sales.svg')) }}" class="w-5 h-5" alt="">
            Sales Tracking
        </a>

        {{-- REPORTS --}}
        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'reports' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
            <img src="{{ asset('images/icons/' . ($active === 'reports' ? 'black-reports.svg' : 'white-reports.svg')) }}" class="w-5 h-5" alt="">
            Reports
        </a>

        {{-- ADMIN ONLY LINKS --}}
        @if(auth()->user()?->isAdmin())

            {{-- USER MANAGEMENT --}}
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'users' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
                <img src="{{ asset('images/icons/' . ($active === 'users' ? 'black-users.svg' : 'white-users.svg')) }}" class="w-5 h-5" alt="">
                User Management
            </a>

            {{-- AUDIT LOG --}}
            <a href="{{ route('audit-log.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active === 'audit-log' ? 'bg-brand-yellow text-brand-black font-semibold' : 'text-neutral-200 hover:bg-neutral-900' }}">
                <img src="{{ asset('images/icons/' . ($active === 'audit-log' ? 'black-audit.svg' : 'white-audit.svg')) }}" class="w-5 h-5" alt="">
                Audit Log
            </a>

        @endif

    </nav>

    {{-- LOGOUT --}}
    <div class="p-4 border-t border-neutral-900">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-neutral-200 hover:bg-neutral-900 w-full text-left">
                <img src="{{ asset('images/icons/white-logout.svg') }}" class="w-5 h-5" alt="">
                Logout
            </button>
        </form>
    </div>

    {{-- FOOTER TAGLINE --}}
    <div class="p-4 text-[10px] text-neutral-600 border-t border-neutral-900 font-body tracking-wide">
        CACAO MACHINES.<br>
        <span class="text-brand-yellow">BETTER POSSIBILITIES.</span>
    </div>

</aside>