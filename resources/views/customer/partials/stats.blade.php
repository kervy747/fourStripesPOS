{{-- CUSTOMER STATS CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    {{-- TOTAL CUSTOMERS --}}
    <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col gap-1">
        <p class="text-xs font-body text-neutral-400 uppercase tracking-wide">Total Customers</p>
        <p class="text-2xl font-heading text-neutral-950">{{ number_format($totalCustomers) }}</p>
    </div>

    {{-- TOTAL REVENUE --}}
    <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col gap-1">
        <p class="text-xs font-body text-neutral-400 uppercase tracking-wide">Total Revenue</p>
        <p class="text-2xl font-heading text-neutral-950">₱{{ number_format($totalRevenue, 2) }}</p>
    </div>

    {{-- AVERAGE SPEND --}}
    <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col gap-1">
        <p class="text-xs font-body text-neutral-400 uppercase tracking-wide">Avg. Spend per Customer</p>
        <p class="text-2xl font-heading text-neutral-950">₱{{ number_format($averageSpend, 2) }}</p>
    </div>

</div>