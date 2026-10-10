<x-layout active="reports">

    <x-page-header title="Reports" subtitle="Sales and inventory records" />

    <main class="p-6 flex-1">

        {{-- TAB BUTTONS --}}
        <div class="flex gap-2 mb-6">
            <a href="{{ route('reports.index', ['tab' => 'sales']) }}"
               class="font-heading font-bold py-2 px-5 rounded-lg text-sm transition bg-neutral-200 text-neutral-700 hover:bg-neutral-100">
                Sales Reports
            </a>
            <a href="{{ route('reports.inventory') }}"
               class="font-heading font-bold py-2 px-5 rounded-lg text-sm transition bg-brand-yellow text-brand-black">
                Inventory Reports
            </a>
        </div>

        {{-- DATE FILTER FORM --}}
        <form method="GET" action="{{ route('reports.inventory') }}" class="flex flex-wrap items-end gap-3 mb-6 bg-white rounded-xl px-4 py-5 shadow-sm">

            <div>
                <label class="block text-sm font-semibold text-neutral-900 mb-1 font-body">Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                       class="border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body text-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-yellow">
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-900 mb-1 font-body">Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                       class="border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body text-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-yellow">
            </div>

            <x-button type="submit" variant="primary">Filter</x-button>

            @if ($dateFrom || $dateTo)
                <a href="{{ route('reports.inventory') }}"
                   class="font-heading font-bold py-3 px-6 rounded-lg text-sm bg-neutral-200 text-neutral-700 hover:bg-neutral-100 transition">
                    Clear
                </a>
            @endif

            {{-- PRINT AS PDF BUTTON --}}
            <a href="{{ route('reports.inventory.print', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
               target="_blank"
               class="font-heading font-bold py-3 px-6 rounded-lg text-sm bg-neutral-200 text-neutral-700 hover:bg-neutral-100 transition ml-auto flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print as PDF
            </a>

        </form>

        {{-- INVENTORY TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-neutral-100">
            <table class="w-full text-sm font-body">
                <thead class="bg-white text-neutral-600 text-left">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Item Code</th>
                        <th class="px-4 py-3 font-semibold">Item</th>
                        <th class="px-4 py-3 font-semibold text-right">Beginning Balance</th>
                        <th class="px-4 py-3 font-semibold text-right">Stock In</th>
                        <th class="px-4 py-3 font-semibold text-right">Stock Out</th>
                        <th class="px-4 py-3 font-semibold text-right">Remaining Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($products as $product)
                        @php
                            $totalStockIn  = $stockIn->get($product->id)?->sum('quantity_added') ?? 0;
                            $totalStockOut = $stockOut->get($product->id)?->sum('quantity') ?? 0;
                            $beginning     = $product->quantity + $totalStockOut - $totalStockIn;
                        @endphp
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-4 py-3 font-heading font-bold text-brand-black">{{ $product->item_code }}</td>
                            <td class="px-4 py-3 text-neutral-900">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-right text-neutral-600">{{ $beginning }}</td>
                            <td class="px-4 py-3 text-right text-neutral-600">{{ $totalStockIn }}</td>
                            <td class="px-4 py-3 text-right text-neutral-600">{{ $totalStockOut }}</td>
                            <td class="px-4 py-3 text-right font-heading font-bold text-brand-black">{{ $product->quantity }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-neutral-400 font-body">
                                No inventory activity found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>

</x-layout>