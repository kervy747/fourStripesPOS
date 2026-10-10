<div class="grid grid-cols-4 gap-4 mb-6">

    {{-- TOTAL TRANSACTIONS --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100 flex items-center gap-4">
        <div class="w-15 h-15 flex items-center justify-center shrink-0 bg-gray-200 rounded-md ">
            <img src="{{ asset('images/icons/black-transactions.svg') }}" alt="Transactions" class="w-8 h-8">
        </div>
        <div>
            <p class="text-xs font-body text-neutral-500 mb-1">Total Transactions</p>
            <p class="text-2xl font-heading font-bold text-brand-black">{{ number_format($totalTransactions) }}</p>
        </div>
    </div>

    {{-- TOTAL REVENUE --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100 flex items-center gap-4">
        <div class="w-15 h-15 flex items-center justify-center shrink-0 bg-gray-200 rounded-md ">
            <img src="{{ asset('images/icons/black-revenue.svg') }}" alt="Revenue" class="w-8 h-8">
        </div>
        <div>
            <p class="text-xs font-body text-neutral-500 mb-1">Total Revenue</p>
            <p class="text-2xl font-heading font-bold text-brand-black">₱{{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    {{-- AVERAGE ORDER VALUE --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100 flex items-center gap-4">
        <div class="w-15 h-15 flex items-center justify-center shrink-0 bg-gray-200 rounded-md ">
            <img src="{{ asset('images/icons/black-average.svg') }}" alt="Average" class="w-8 h-8">
        </div>
        <div>
            <p class="text-xs font-body text-neutral-500 mb-1">Average Order Value</p>
            <p class="text-2xl font-heading font-bold text-brand-black">₱{{ number_format($averageOrderValue, 2) }}</p>
        </div>
    </div>

    {{-- TOTAL ITEMS SOLD --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100 flex items-center gap-4">
        <div class="w-15 h-15 flex items-center justify-center shrink-0 bg-gray-200 rounded-md ">
            <img src="{{ asset('images/icons/black-items.svg') }}" alt="Items" class="w-8 h-8">
        </div>
        <div>
            <p class="text-xs font-body text-neutral-500 mb-1">Total Items Sold</p>
            <p class="text-2xl font-heading font-bold text-brand-black">{{ number_format($totalItemsSold) }}</p>
        </div>
    </div>

</div>