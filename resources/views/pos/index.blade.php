<x-layout active="pos">

    <x-page-header title="Point of Sale" subtitle="Search and add items to the current transaction" />

    <main class="p-6 flex-1">

        {{-- INFO / ERROR MESSAGES --}}
        @if(session('info'))
            <div class="bg-info-tint text-info p-3 rounded-lg mb-4 text-sm">{{ session('info') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-danger-tint text-danger p-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
        @endif

        <div class="flex flex-col lg:flex-row gap-4">

            {{-- LEFT: PRODUCT BROWSING --}}
            <div class="flex-1">

                {{-- SEARCH --}}
                <form method="GET" action="{{ route('pos.index') }}" class="mb-4">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by name or ID number..."
                           class="w-full border border-neutral-200 bg-neutral-0 rounded-lg py-3 px-4 text-sm">
                </form>

                {{-- CATEGORY TABS --}}
                <div class="flex gap-2 mb-4">
                    <a href="{{ route('pos.index', ['search' => request('search')]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold {{ !request('category') || request('category') === 'all' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700' }}">
                        All Items
                    </a>
                    <a href="{{ route('pos.index', ['category' => 'machines', 'search' => request('search')]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('category') === 'machines' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700' }}">
                        Machines
                    </a>
                    <a href="{{ route('pos.index', ['category' => 'tools', 'search' => request('search')]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('category') === 'tools' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700' }}">
                        Tools
                    </a>
                    <a href="{{ route('pos.index', ['category' => 'accessories', 'search' => request('search')]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('category') === 'accessories' ? 'bg-brand-yellow text-brand-black' : 'bg-neutral-0 text-neutral-700' }}">
                        Accessories
                    </a>
                </div>

                {{-- PRODUCT LIST --}}
                <div class="bg-neutral-0 rounded-xl shadow-sm overflow-hidden">
                    @forelse($products as $product)
                        <form method="POST" action="{{ route('pos.add', $product) }}">
                            @csrf
                            <button type="submit"
                                    @if($product->quantity == 0) disabled @endif
                                    class="w-full flex items-center justify-between px-4 py-3 border-b border-neutral-100 text-left {{ $product->quantity == 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neutral-100' }}">

                                <div class="flex items-center gap-4">
                                    <span class="text-xs text-neutral-500 w-16">{{ $product->item_code }}</span>
                                    <span class="font-semibold text-neutral-900">{{ $product->name }}</span>
                                </div>

                                <div class="flex items-center gap-4">
                                    <span class="font-semibold text-neutral-900">&#8369; {{ number_format($product->price, 2) }}</span>

                                    @if($product->status === 'out_of_stock')
                                        <span class="bg-danger-tint text-danger text-xs font-semibold px-3 py-1 rounded-full">Out of Stock</span>
                                    @elseif($product->status === 'low_stock')
                                        <span class="bg-warning-tint text-warning text-xs font-semibold px-3 py-1 rounded-full">{{ $product->quantity }} in stock</span>
                                    @else
                                        <span class="bg-success-tint text-success text-xs font-semibold px-3 py-1 rounded-full">{{ $product->quantity }} in stock</span>
                                    @endif
                                </div>

                            </button>
                        </form>
                    @empty
                        <p class="p-6 text-center text-neutral-500 text-sm">No products found.</p>
                    @endforelse
                </div>

                {{-- PAGINATION (DEFAULT LARAVEL TAILWIND STYLE) --}}
                <div class="mt-4">
                    {{ $products->links() }}
                </div>

            </div>

            @include('pos.partials.cart')

        </div>

    </main>

</x-layout>