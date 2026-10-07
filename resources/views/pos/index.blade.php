<x-layout active="pos">

    <x-page-header title="Point of Sale" subtitle="Search and add items to the current transaction" />

    <main class="p-6 flex-1">

        <div class="flex flex-col lg:flex-row gap-4">

            {{-- LEFT: PRODUCT BROWSING --}}
            <div class="flex-1">
                
                {{-- CUSTOMER INPUT CONTAINER --}}
                <div class="bg-neutral-0 rounded-xl shadow-sm p-4 mb-4">
                    {{-- CUSTOMER --}}
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-md font-semibold text-brand-black">Customer Information</label>
                        </div>

                        {{-- NAME + FIND --}}
                        <div class="flex gap-2 mb-2">

                            <div class="flex h-10 items-center flex-1 bg-white border-2 border-neutral-300 rounded-lg py-2 px-3 gap-2 focus-within:border-black">
                                <img
                                    src="{{ asset('images/icons/black-search.svg') }}"
                                    alt="Search"
                                    class="w-4 h-4 opacity-60">
                                <input
                                    type="text"
                                    form="cart-form"
                                    name="customer_name"
                                    value="{{ $customerName }}"
                                    placeholder="Enter customer name or company name..."
                                    class="flex-1 text-sm focus:outline-none"
                                    onkeydown="if(event.key === 'Enter'){ event.preventDefault(); }">
                            </div>

                            @unless($selectedCustomerId)
                                <button
                                    type="submit"
                                    form="cart-form"
                                    formaction="{{ route('pos.customer.update') }}"
                                    name="find_customer"
                                    value="1"
                                    class="bg-brand-yellow-tint text-brand-yellow-deep text-xs font-semibold px-3 rounded-lg border-2 border-brand-yellow">
                                    Find
                                </button>
                            @endunless

                            @if($selectedCustomerId)
                                <button
                                    type="submit"
                                    form="cart-form"
                                    formaction="{{ route('pos.customer.update') }}"
                                    name="clear_customer"
                                    value="1"
                                    class="bg-brand-yellow-tint text-brand-yellow-deep text-xs font-semibold px-3 rounded-lg border-2 border-brand-yellow">
                                    Search again
                                </button>
                            @endif

                        </div>

                        <div class="flex gap-2">
                            {{-- PHONE (OPTIONAL) --}}
                            <div class="w-[35%] flex h-10 items-center flex-1 bg-white border-2 border-neutral-300 rounded-lg py-2 px-3 gap-2 focus-within:border-black">
                                <img src="{{ asset('images/icons/black-phone.svg') }}" alt="phone" class="w-4 h-4 opacity-60">
                                <input type="text" form="cart-form" name="customer_phone" value="{{ $customerPhone }}" placeholder="Phone # (optional)"
                                class="w-full bg-white rounded-lg text-sm focus:outline-none"
                                onkeydown="if(event.key === 'Enter'){ event.preventDefault(); }">
                            </div>

                            {{-- ADDRESS (REQUIRED) --}}
                            <div class="w-[65%] flex h-10 items-center flex-1 bg-white border-2 border-neutral-300 rounded-lg py-2 px-3 gap-2 focus-within:border-black">
                                <img src="{{ asset('images/icons/black-location.svg') }}" alt="location" class="w-4 h-4 opacity-60">
                                 <input type="text" form="cart-form" name="customer_address" value="{{ $customerAddress }}" placeholder="Address"
                                class="w-full bg-white rounded-lg text-sm focus:outline-none"
                                onkeydown="if(event.key === 'Enter'){ event.preventDefault(); }">
                            </div>
                        </div>

                        {{-- CUSTOMER ERRORS --}}
                        <x-error name="customer_address" />
                        <x-error name="customer_name" />

                        {{-- MATCHED CUSTOMERS --}}
                        @if(!$selectedCustomerId && count($customerMatches) > 0)
                            <div class="mt-3">
                                <div class="text-xs font-medium text-neutral-700">Matching Customers</div>
                                <div class="mt-2 flex border bg-neutral-200 border-neutral-200 rounded-lg mb-2 max-h-32 overflow-y-auto p-1 gap-2">
                                    @foreach($customerMatches as $match)
                                        <button type="submit" form="cart-form" name="select_customer" formaction="{{ route('pos.customer.update') }}" value="{{ $match['id'] }}"
                                                class="w-full text-left px-3 py-2 text-xs bg-white rounded-lg border-b border-neutral-100 hover:bg-neutral-100">
                                            <p class="font-semibold text-neutral-900">{{ $match['name'] }}</p>
                                            <p class="text-neutral-500">{{ $match['phone_number'] ?? 'No phone on file' }}</p>
                                            <p class="text-neutral-500">{{ $match['address'] }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- PRODUCT SEARCH CONTAINER --}}
                <div class="bg-neutral-0 rounded-xl shadow-sm p-4 mb-4">
                    <div class="text-md font-semibold text-brand-black mb-2">Products</div>

                    {{-- SEARCH --}}
                    <form method="GET" action="{{ route('pos.index') }}" class="mb-4">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <div class="flex h-10 items-center flex-1 bg-white border-2 border-neutral-300 rounded-lg py-2 px-3 gap-2 focus-within:border-black">
                            <img src="{{ asset('images/icons/black-search.svg') }}" alt="Search" class="w-4 h-4 opacity-60">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by name or ID number..." class="flex-1 text-sm focus:outline-none">
                        </div>
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
                    <div class="bg-neutral-0 rounded-xl shadow-[0_0_1px_0px_black] overflow-hidden">

                        {{-- HEADER --}}
                        <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-neutral-200 text-xs font-semibold text-neutral-500">
                            <div class="flex items-center gap-4">
                                <span class="w-16">Item Code</span>
                                <span>Product Name</span>
                            </div>

                            <div class="flex items-center gap-20">
                                <span>Price</span>
                                <span>Stock</span>
                            </div>
                        </div>

                        {{-- PRODUCTS --}}
                        @forelse($products as $product)
                            <form method="POST" action="{{ route('pos.add', $product) }}">
                                @csrf

                                <button type="submit"
                                    @if($product->quantity == 0) disabled @endif
                                    class="w-full flex items-center justify-between px-4 py-3 border-b border-neutral-100 text-left {{ $product->quantity == 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neutral-100' }}">

                                    <div class="flex items-center gap-4">
                                        <span class="text-xs text-neutral-500 w-16">
                                            {{ $product->item_code }}
                                        </span>

                                        <span class="font-semibold text-neutral-900">
                                            {{ $product->name }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <span class="font-semibold text-neutral-900">
                                            ₱ {{ number_format($product->price, 2) }}
                                        </span>

                                        @if($product->status === 'out_of_stock')
                                            <span class="bg-danger-tint text-danger text-xs font-semibold px-3 py-1 rounded-full">
                                                Out of Stock
                                            </span>
                                        @elseif($product->status === 'low_stock')
                                            <span class="bg-warning-tint text-warning text-xs font-semibold px-3 py-1 rounded-full">
                                                {{ $product->quantity }} in stock
                                            </span>
                                        @else
                                            <span class="bg-success-tint text-success text-xs font-semibold px-3 py-1 rounded-full">
                                                {{ $product->quantity }} in stock
                                            </span>
                                        @endif
                                    </div>

                                </button>
                            </form>
                        @empty
                            <p class="p-6 text-center text-neutral-500 text-sm">
                                No products found.
                            </p>
                        @endforelse

                    </div>
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