<x-layout active="inventory">

    {{-- PAGE HEADER --}}
    <x-page-header title="Inventory" subtitle="Manage your machines, tools, and accessories" />

    <main class="p-6 flex-1">

        {{-- SUMMARY CARDS --}}
        @include('inventory.stat-card')

        {{-- CATEGORY TABS --}}
        <div class="flex items-center justify-between mb-4">

            <div class="flex gap-2">
                <a href="{{ route('inventory.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold {{ !request('category') || request('category') === 'all' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700' }}">
                    All Items
                </a>
                <a href="{{ route('inventory.index', ['category' => 'machines']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('category') === 'machines' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700' }}">
                    Machines
                </a>
                <a href="{{ route('inventory.index', ['category' => 'tools']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('category') === 'tools' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700' }}">
                    Tools
                </a>
                <a href="{{ route('inventory.index', ['category' => 'accessories']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('category') === 'accessories' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700' }}">
                    Accessories
                </a>
            </div>

            <a href="{{ route('inventory.create') }}"
               class="bg-brand-yellow text-brand-black font-heading font-bold px-4 py-2 rounded-lg text-sm">
                + Add Item
            </a>

        </div>

        {{-- FILTERS + SEARCH --}}
        <form method="GET" action="{{ route('inventory.index') }}" class="bg-neutral-0 rounded-xl p-4 shadow-sm mb-4 flex flex-wrap items-end gap-4">

            {{-- KEEP CATEGORY TAB SELECTION WHEN FILTERING --}}
            <input type="hidden" name="category" value="{{ request('category') }}">

            {{-- STOCK STATUS --}}
            <div>
                <label class="block text-xs font-semibold text-neutral-700 mb-1">Stock Status</label>
                <select name="stock_status" class="border border-neutral-200 rounded-lg py-2 px-3 text-sm">
                    <option value="">All Status</option>
                    <option value="in_stock" @selected(request('stock_status') === 'in_stock')>In Stock</option>
                    <option value="low_stock" @selected(request('stock_status') === 'low_stock')>Low Stock</option>
                    <option value="out_of_stock" @selected(request('stock_status') === 'out_of_stock')>Out of Stock</option>
                </select>
            </div>

            {{-- SEARCH --}}
                           

            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-neutral-700 mb-1">Search</label>
                <div class="flex h-10 items-center flex-1 bg-white border border-neutral-300 rounded-lg py-2 px-3 gap-2 focus-within:border-black focus-within:border-2">
                    <img src="{{ asset('images/icons/black-search.svg') }}" alt="Search" class="w-4 h-4 opacity-60">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item name or code..." class="flex-1 text-sm focus:outline-none">
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex gap-2">
                <a href="{{ route('inventory.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold bg-neutral-100 text-neutral-700">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold bg-brand-yellow text-brand-black">
                    Apply Filter
                </button>
            </div>

        </form>

        {{-- INVENTORY TABLE --}}
        <div class="bg-neutral-0 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-neutral-200 text-left text-neutral-600">
                    <tr>
                        <th class="p-3">Item Code</th>
                        <th class="p-3">Item Name</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Stock Qty</th>
                        <th class="p-3">Reorder Level</th>
                        <th class="p-3">Unit Cost</th>
                        <th class="p-3">Selling Price</th>
                        <th class="p-3">Status</th>
                        <th class="p-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b border-neutral-100">
                            <td class="p-3 text-neutral-600">{{ $product->item_code }}</td>
                            <td class="p-3 font-semibold text-neutral-900">{{ $product->name }}</td>
                            <td class="p-3 text-neutral-600 capitalize">{{ $product->category }}</td>
                            <td class="p-3 text-neutral-900">{{ $product->quantity }}</td>
                            <td class="p-3 text-neutral-600">{{ $product->reorder_level }}</td>
                            <td class="p-3 text-neutral-900">&#8369; {{ number_format($product->unit_cost, 2) }}</td>
                            <td class="p-3 text-neutral-900">&#8369; {{ number_format($product->price, 2) }}</td>
                            <td class="p-3">
                                @if($product->status === 'in_stock')
                                    <span class="bg-success-tint text-success text-xs font-semibold px-3 py-1 rounded-full">In Stock</span>
                                @elseif($product->status === 'low_stock')
                                    <span class="bg-warning-tint text-warning text-xs font-semibold px-3 py-1 rounded-full">Low Stock</span>
                                @else
                                    <span class="bg-danger-tint text-danger text-xs font-semibold px-3 py-1 rounded-full">Out of Stock</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <a href="{{ route('inventory.edit', $product->id) }}">
                                    <img src="{{ asset('images/icons/grey-edit.svg') }}" class="w-4 h-4" alt="Edit">
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-neutral-500">
                                No items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION (DEFAULT LARAVEL TAILWIND STYLE) --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>

    </main>

</x-layout>