<x-layout active="inventory">

    {{-- PAGE HEADER --}}
    <x-page-header title="Inventory" subtitle="Manage your machines, tools, and accessories" />

    <main class="p-6 flex-1">

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            <div class="bg-neutral-0 rounded-xl p-4 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-info-tint flex items-center justify-center">
                    <img src="{{ asset('images/icons/blue-hashtag.svg') }}" class="w-5 h-5" alt="">
                </div>
                <div>
                    <p class="text-xl font-heading font-bold text-neutral-900">{{ $totalItems }}</p>
                    <p class="text-xs text-neutral-600">Total Items</p>
                </div>
            </div>

            <div class="bg-neutral-0 rounded-xl p-4 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-success-tint flex items-center justify-center">
                    <img src="{{ asset('images/icons/green-check.svg') }}" class="w-5 h-5" alt="">
                </div>
                <div>
                    <p class="text-xl font-heading font-bold text-neutral-900">{{ $inStockCount }}</p>
                    <p class="text-xs text-neutral-600">In Stock</p>
                </div>
            </div>

            <div class="bg-neutral-0 rounded-xl p-4 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-warning-tint flex items-center justify-center">
                    <img src="{{ asset('images/icons/orange-warning.svg') }}" class="w-5 h-5" alt="">
                </div>
                <div>
                    <p class="text-xl font-heading font-bold text-neutral-900">{{ $lowStockCount }}</p>
                    <p class="text-xs text-neutral-600">Low Stock</p>
                </div>
            </div>

            <div class="bg-neutral-0 rounded-xl p-4 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-danger-tint flex items-center justify-center">
                    <img src="{{ asset('images/icons/red-remove.svg') }}" class="w-5 h-5" alt="">
                </div>
                <div>
                    <p class="text-xl font-heading font-bold text-neutral-900">{{ $outOfStockCount }}</p>
                    <p class="text-xs text-neutral-600">Out of Stock</p>
                </div>
            </div>

        </div>

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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item name or code..."
                       class="w-full border border-neutral-200 rounded-lg py-2 px-3 text-sm">
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
                        <th class="p-3">Unit</th>
                        <th class="p-3">Standard Level</th>
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
                            <td class="p-3 text-neutral-600">{{ $product->unit }}</td>
                            <td class="p-3 text-neutral-600">{{ $product->standard_level }}</td>
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
                            <td colspan="10" class="p-6 text-center text-neutral-500">
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