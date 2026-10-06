<x-layout active="sales">

    {{-- PAGE HEADER --}}
    <x-page-header title="Sales Tracking" />

    <div class="flex-1 flex overflow-hidden">

        {{-- LEFT: TRANSACTIONS --}}
        <div class="flex-1 p-6 overflow-y-auto">

            {{-- STATUS TABS --}}
            <div class="flex items-center gap-2 mb-4">
                <a href="{{ route('sales.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}"
                   class="font-heading font-semibold text-sm px-5 py-2 rounded-full {{ $status === 'all' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700 border border-neutral-200' }}">
                    All Transactions
                </a>
                <a href="{{ route('sales.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}"
                   class="font-heading font-semibold text-sm px-5 py-2 rounded-full {{ $status === 'pending' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700 border border-neutral-200' }}">
                    Pending
                </a>
                <a href="{{ route('sales.index', array_merge(request()->except(['status', 'page']), ['status' => 'completed'])) }}"
                   class="font-heading font-semibold text-sm px-5 py-2 rounded-full {{ $status === 'completed' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700 border border-neutral-200' }}">
                    Completed
                </a>
            </div>

            {{-- SEARCH --}}
            <form method="GET" action="{{ route('sales.index') }}" class="mb-4">
                <input type="hidden" name="status" value="{{ $status }}">
                <x-input name="search" type="text" placeholder="Search by OR number or customer..." value="{{ request('search') }}" />
            </form>

            {{-- TRANSACTIONS TABLE --}}
            <div class="bg-neutral-0 rounded-xl border border-neutral-200 overflow-hidden">
                <table class="w-full text-sm font-body">
                    <thead class="bg-neutral-100 text-neutral-600 text-xs uppercase font-heading">
                        <tr>
                            <th class="text-left px-4 py-3">OR No.</th>
                            <th class="text-left px-4 py-3">Date &amp; Time</th>
                            <th class="text-left px-4 py-3">Customer</th>
                            <th class="text-left px-4 py-3">Items</th>
                            <th class="text-left px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            @php
                                $orNumber = 'OR-' . $sale->created_at->format('Y') . '-' . str_pad($sale->id, 4, '0', STR_PAD_LEFT);
                                $itemNames = $sale->items->map(fn ($item) => $item->product->name ?? '')->join(', ');
                                $isSelected = $selectedSale && $selectedSale->id === $sale->id;
                            @endphp
                            <tr class="relative border-t border-neutral-200 cursor-pointer {{ $isSelected ? 'bg-brand-yellow-tint' : 'hover:bg-neutral-100' }}">
                                <td class="px-4 py-3 font-semibold text-neutral-900">
                                    {{-- STRETCHED LINK: MAKES THE WHOLE ROW CLICKABLE WITHOUT JS --}}
                                    <a href="{{ route('sales.index', array_merge(request()->query(), ['selected' => $sale->id])) }}"
                                       class="absolute inset-0 z-0"></a>
                                    <span class="relative z-10">{{ $orNumber }}</span>
                                </td>
                                <td class="px-4 py-3 text-neutral-600">
                                    {{ $sale->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-4 py-3 text-neutral-900">
                                    {{ $sale->customer->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-neutral-600">
                                    {{ \Illuminate\Support\Str::limit($itemNames, 30) }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-neutral-900">
                                    ₱ {{ number_format($sale->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-neutral-600">
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $sales->links() }}
            </div>

        </div>

        {{-- RIGHT: TRANSACTION DETAILS --}}
        <div class="w-96 bg-neutral-0 border-l border-neutral-200 p-6 overflow-y-auto">

            @if ($selectedSale)
                @php
                    $orNumber = 'OR-' . $selectedSale->created_at->format('Y') . '-' . str_pad($selectedSale->id, 4, '0', STR_PAD_LEFT);
                @endphp

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-heading font-bold text-lg text-neutral-900">Transaction Details</h2>
                    <a href="{{ route('sales.index', request()->except('selected')) }}" class="text-neutral-600 hover:text-neutral-900">&times;</a>
                </div>

                {{-- BASIC INFO --}}
                <div class="space-y-3 mb-6 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-600">OR Number</span>
                        <span class="font-semibold text-neutral-900">{{ $orNumber }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Customer Name</span>
                        <span class="font-semibold text-neutral-900">{{ $selectedSale->customer->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Processed By</span>
                        <span class="font-semibold text-neutral-900">{{ $selectedSale->user->getFullNameAttribute() ?? '—' }}</span>
                    </div>
                </div>

                {{-- ITEMIZED BREAKDOWN --}}
                <p class="text-xs font-heading font-semibold text-neutral-600 uppercase mb-3">Itemized Breakdown</p>
                <div class="space-y-4 mb-6">
                    @foreach ($selectedSale->items as $item)
                        <div class="flex justify-between">
                            <div>
                                <p class="font-semibold text-neutral-900 text-sm">{{ $item->product->name ?? '—' }}</p>
                                <p class="text-xs text-neutral-600">Qty: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-semibold text-neutral-900 text-sm">
                                ₱ {{ number_format($item->price * $item->quantity, 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- TOTAL --}}
                <div class="flex justify-between items-center border-t border-neutral-200 pt-4 mb-6">
                    <span class="font-heading font-bold text-neutral-900">Total Paid</span>
                    <span class="font-heading font-bold text-brand-yellow-deep text-lg">₱ {{ number_format($selectedSale->total, 2) }}</span>
                </div>

                {{-- ACTIONS --}}
                @if ($selectedSale->status === 'pending')
                    <form method="POST" action="{{ route('sales.complete', $selectedSale) }}">
                        @csrf
                        @method('PATCH')
                        <x-button type="submit" variant="primary" class="w-full">Mark as Completed</x-button>
                    </form>
                @endif

            @else
                {{-- EMPTY STATE --}}
                <div class="h-full flex flex-col items-center justify-center text-center text-neutral-600">
                    <p class="font-heading font-semibold text-neutral-900 mb-1">No Transaction Selected</p>
                    <p class="text-sm">Click a transaction from the list to view its details here.</p>
                </div>
            @endif

        </div>

    </div>

</x-layout>