<x-layout active="customers">

    {{-- PAGE HEADER --}}
    <x-page-header title="Customer Profile" subtitle="Viewing order history and spending summary" />

    <main class="p-6 flex-1 flex flex-col gap-6">

        {{-- BACK BUTTON --}}
        <div>
            <a href="{{ route('customer.index') }}" class="inline-flex items-center gap-2 text-sm font-heading text-neutral-500 hover:text-neutral-900 transition">
                ← Back to Customers
            </a>
        </div>

        {{-- CUSTOMER INFO CARD --}}
        <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col sm:flex-row gap-6 items-start">

            {{-- DETAILS --}}
            <div class="flex-1 flex flex-col gap-1">
                <h2 class="text-xl font-heading text-neutral-950">{{ $customer->name }}</h2>
                <p class="text-sm font-body text-neutral-500">{{ $customer->phone_number ?? 'No phone number' }}</p>
                <p class="text-sm font-body text-neutral-500">{{ $customer->address ?? 'No address' }}</p>
            </div>

            {{-- SPEND SUMMARY --}}
            <div class="flex gap-6">
                <div class="flex flex-col items-center gap-1">
                    <p class="text-xs font-body text-neutral-400 uppercase tracking-wide">Total Orders</p>
                    <p class="text-2xl font-heading text-neutral-950">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="w-px bg-neutral-100"></div>
                <div class="flex flex-col items-center gap-1">
                    <p class="text-xs font-body text-neutral-400 uppercase tracking-wide">Total Spent</p>
                    <p class="text-2xl font-heading text-neutral-950">₱{{ number_format($totalSpent, 2) }}</p>
                </div>
            </div>

        </div>

        {{-- SALES HISTORY --}}
        <div class="flex flex-col gap-4">

            <h3 class="text-sm font-heading text-neutral-500 uppercase tracking-wide">Sales History</h3>

            @forelse ($sales as $sale)

                {{-- SALE CARD --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                    {{-- SALE HEADER --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 px-5 py-4 border-b border-neutral-100">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-xs font-body text-neutral-400">Sale #{{ $sale->id }}</span>
                            <span class="text-sm font-heading text-neutral-700">{{ $sale->created_at->format('M d, Y  h:i A') }}</span>
                        </div>
                        <div class="flex flex-col sm:items-end gap-0.5">
                            <span class="text-xs font-body text-neutral-400">Processed by: {{ $sale->user->name ?? '—' }}</span>
                            <span class="text-base font-heading text-neutral-950">₱{{ number_format($sale->total, 2) }}</span>
                        </div>
                    </div>

                    {{-- SALE ITEMS --}}
                    <table class="w-full text-sm font-body">
                        <thead class="bg-neutral-50 text-neutral-400 text-xs font-heading uppercase tracking-wide">
                            <tr>
                                <th class="px-5 py-2 text-left">Item</th>
                                <th class="px-5 py-2 text-center">Qty</th>
                                <th class="px-5 py-2 text-right">Price</th>
                                <th class="px-5 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-50">
                            @forelse ($sale->items as $item)
                                <tr>
                                    <td class="px-5 py-2 text-neutral-700">{{ $item->item_name }}</td>
                                    <td class="px-5 py-2 text-center text-neutral-600">{{ $item->quantity }}</td>
                                    <td class="px-5 py-2 text-right text-neutral-600">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="px-5 py-2 text-right font-heading text-neutral-900">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-4 text-center text-neutral-300 font-body text-xs">No items recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

            @empty

                {{-- EMPTY STATE --}}
                <div class="bg-white rounded-xl shadow-sm px-6 py-16 flex flex-col items-center gap-2 text-center">
                    <p class="text-neutral-400 font-body text-sm">This customer has no orders yet.</p>
                </div>

            @endforelse

            {{-- PAGINATION --}}
            <div>
                {{ $sales->links() }}
            </div>

        </div>

    </main>

</x-layout>