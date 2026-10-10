<x-layout active="dashboard">

    {{-- PAGE HEADER --}}
    <x-page-header title="Dashboard" subtitle="Welcome to your dashboard"></x-page-header>

    <main class="p-6 flex-1">

        {{-- STAT CARDS --}}
        @include('inventory.stat-card')

        {{-- OUT OF STOCK + LOW STOCK LISTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

            {{-- OUT OF STOCK LIST --}}
            <div class="bg-neutral-0 rounded-xl shadow-sm p-4">
                <div class="text-md font-semibold text-brand-black mb-3">Out of Stock</div>

                @if($outOfStockProducts->isEmpty())
                    <div class="bg-success-tint text-success text-sm font-semibold px-4 py-3 rounded-lg">
                        All items are currently in stock.
                    </div>
                @else
                    <div class="rounded-xl shadow-[0_0_1px_0px_black] overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2 bg-white border-b border-neutral-200 text-xs font-semibold text-neutral-500">
                            <span>Product Name</span>
                            <span>Category</span>
                        </div>
                        @foreach($outOfStockProducts as $product)
                            <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-100 bg-white">
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900">{{ $product->name }}</p>
                                    <p class="text-xs text-neutral-400">{{ $product->item_code }}</p>
                                </div>
                                <span class="text-xs font-semibold text-neutral-500 capitalize">{{ $product->category }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- LOW STOCK LIST --}}
            <div class="bg-neutral-0 rounded-xl shadow-sm p-4">
                <div class="text-md font-semibold text-brand-black mb-3">Low Stock</div>

                @if($lowStockProducts->isEmpty())
                    <div class="bg-success-tint text-success text-sm font-semibold px-4 py-3 rounded-lg">
                        No items are running low.
                    </div>
                @else
                    <div class="rounded-xl shadow-[0_0_1px_0px_black] overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2 bg-white border-b border-neutral-200 text-xs font-semibold text-neutral-500">
                            <span>Product Name</span>
                            <span>Qty Left</span>
                        </div>
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-100 bg-white">
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900">{{ $product->name }}</p>
                                    <p class="text-xs text-neutral-400">{{ $product->item_code }}</p>
                                </div>
                                <span class="bg-warning-tint text-warning text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $product->quantity }} left
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- TOP PRODUCTS THIS MONTH --}}
        <div class="bg-neutral-0 rounded-xl shadow-sm p-4">
            <div class="text-md font-semibold text-brand-black mb-3">
                Top Products — {{ \Carbon\Carbon::now()->format('F Y') }}
            </div>

            @if($topProducts->isEmpty())
                <p class="text-sm text-neutral-500">No sales recorded yet this month.</p>
            @else
                <div class="rounded-xl shadow-[0_0_1px_0px_black] overflow-hidden">

                    {{-- TABLE HEADER --}}
                    <div class="grid grid-cols-2 border-b border-neutral-200 bg-white">
                        <div class="flex items-center gap-4 px-4 py-2 border-r border-neutral-200 text-xs font-semibold text-neutral-500">
                            <span class="flex-1">Product Name</span>
                            <span class="w-16 text-right">Units</span>
                            <span class="w-24 text-right">Amount</span>
                        </div>
                        <div class="flex items-center gap-4 px-4 py-2 text-xs font-semibold text-neutral-500">
                            <span class="flex-1">Product Name</span>
                            <span class="w-16 text-right">Units</span>
                            <span class="w-24 text-right">Amount</span>
                        </div>
                    </div>

                    {{-- TABLE ROWS (2 PRODUCTS PER ROW) --}}
                    @foreach($topProducts->chunk(2) as $pair)
                        <div class="grid grid-cols-2 border-b border-neutral-100 bg-white">

                            {{-- LEFT ENTRY --}}
                            <div class="flex items-center gap-4 px-4 py-3 border-r border-neutral-200">
                                <span class="flex-1 text-sm font-semibold text-neutral-900">{{ $pair->first()->item_name }}</span>
                                <span class="w-16 text-right text-sm text-neutral-600">{{ $pair->first()->total_units }} units</span>
                                <span class="w-24 text-right text-sm font-semibold text-brand-yellow-deep">₱ {{ number_format($pair->first()->total_revenue, 2) }}</span>
                            </div>

                            {{-- RIGHT ENTRY (MAY BE EMPTY IF ODD NUMBER) --}}
                            @if($pair->count() > 1)
                                <div class="flex items-center gap-4 px-4 py-3">
                                    <span class="flex-1 text-sm font-semibold text-neutral-900">{{ $pair->last()->item_name }}</span>
                                    <span class="w-16 text-right text-sm text-neutral-600">{{ $pair->last()->total_units }} units</span>
                                    <span class="w-24 text-right text-sm font-semibold text-brand-yellow-deep">₱ {{ number_format($pair->last()->total_revenue, 2) }}</span>
                                </div>
                            @else
                                <div class="px-4 py-3"></div>
                            @endif

                        </div>
                    @endforeach

                </div>
            @endif
        </div>

    </main>

</x-layout>