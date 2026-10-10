<x-layout active="dashboard">
    
    {{-- PAGE HEADER --}}
    <x-page-header title="Dashboard" subtitle="Welcome to your dashboard"></x-page-header>

    <main class="p-6 flex-1">
        {{-- SUMMARY CARDS --}}
        @include('inventory.stat-card')
    </main>

</x-layout>