{{-- RIGHT: CURRENT TRANSACTION --}}
<div class="w-full lg:w-130 bg-neutral-0 rounded-xl shadow-sm p-4 flex flex-col h-[calc(100vh-130px)] sticky top-6">

    <h2 class="font-heading font-bold text-lg text-neutral-900 mb-4">Current Transaction</h2>

    <form method="POST" action="{{ route('pos.cart.update') }}" class="flex flex-col flex-1 min-h-0">
        @csrf

        {{-- CART ITEMS --}}
        <div class="flex-1 overflow-y-auto space-y-2 mb-2">
            @forelse($cart as $item)
                <div class="border-b border-neutral-100 pb-1">

                    <div class="flex justify-between items-start">

                        <div>
                            <p class="font-semibold text-neutral-900 text-sm">{{ $item['name'] }}</p>
                            <p class="text-xs text-neutral-500">{{ $item['item_code'] }}</p>
                        </div>

                        <div class="flex items-center gap-2">

                            <span class="text-sm font-semibold text-neutral-900">
                                &#8369; {{ number_format($item['price'] * $item['quantity'], 2) }}
                            </span>

                            <button type="submit" name="decrease" value="{{ $item['product_id'] }}"
                                    class="w-7 h-7 rounded bg-neutral-100 text-neutral-700 text-sm font-bold">-
                            </button>
                            <span class="text-sm w-6 text-center">{{ $item['quantity'] }}</span>
                            <button type="submit" name="increase" value="{{ $item['product_id'] }}"
                                    class="w-7 h-7 rounded bg-neutral-100 text-neutral-700 text-sm font-bold">+
                            </button>

                            <button type="submit" name="remove" value="{{ $item['product_id'] }}">
                                <img src="{{ asset('images/icons/red-remove.svg') }}" class="w-4 h-4" alt="Remove">
                            </button>

                        </div>

                    </div>

                </div>
            @empty
                <p class="text-sm text-neutral-500 text-center py-8">
                    No items yet. Click a product on the left to add it.
                </p>
            @endforelse
        </div>

        {{-- NOTES --}}
        <div class="mb-3">
            <label class="block text-xs font-semibold text-neutral-700 mb-1">Notes (Optional)</label>
            <textarea name="notes" rows="2" placeholder="Add notes for this transaction..."
                      class="w-full border border-neutral-200 rounded-lg py-2 px-3 text-sm">{{ $notes }}</textarea>
        </div>

        {{-- CUSTOMER --}}
        <div class="mb-3">
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-neutral-700">Customer Name</label>
                @if($selectedCustomerId)
                    <button type="submit" name="clear_customer" value="1" class="text-xs text-brand-yellow-deep font-semibold">
                        Search again
                    </button>
                @endif
            </div>

            {{-- NAME + FIND --}}
            <div class="flex gap-2 mb-2">
                <input type="text" name="customer_name" value="{{ $customerName }}" placeholder="Enter customer name..."
                       class="flex-1 border border-neutral-200 rounded-lg py-2 px-3 text-sm">
                @unless($selectedCustomerId)
                    <button type="submit" name="find_customer" value="1" class="bg-neutral-100 text-neutral-700 text-xs font-semibold px-3 rounded-lg">
                        Find
                    </button>
                @endunless
            </div>

            {{-- MATCHED CUSTOMERS --}}
            @if(!$selectedCustomerId && count($customerMatches) > 0)
                <div class="border border-neutral-200 rounded-lg mb-2 max-h-32 overflow-y-auto">
                    @foreach($customerMatches as $match)
                        <button type="submit" name="select_customer" value="{{ $match['id'] }}"
                                class="w-full text-left px-3 py-2 text-xs border-b border-neutral-100 hover:bg-neutral-100">
                            <p class="font-semibold text-neutral-900">{{ $match['name'] }}</p>
                            <p class="text-neutral-500">{{ $match['phone_number'] ?? 'No phone on file' }}</p>
                            <p class="text-neutral-500">{{ $match['address'] }}</p>
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="flex gap-2">
                {{-- PHONE (OPTIONAL) --}}
                <input type="text" name="customer_phone" value="{{ $customerPhone }}" placeholder="Phone # (optional)"
                    class="w-[35%] border border-neutral-200 rounded-lg py-2 px-3 text-sm">

                {{-- ADDRESS (REQUIRED) --}}
                <input type="text" name="customer_address" value="{{ $customerAddress }}" placeholder="Address"
                    class="w-[65%] border border-neutral-200 rounded-lg py-2 px-3 text-sm">
                <x-error name="customer_address" />

            </div>

        </div>

        {{-- PAYMENT METHOD --}}
        <div class="mb-3">
            <label class="block text-xs font-semibold text-neutral-700 mb-1">Payment Method</label>
            <div class="flex gap-4 text-sm">
                <label class="flex items-center gap-1">
                    <input type="radio" name="payment_method" value="cash" checked>
                    Cash
                </label>
                <label class="flex items-center gap-1">
                    <input type="radio" name="payment_method" value="cashless">
                    Cashless
                </label>
            </div>
            <x-error name="payment_method" />
        </div>

        {{-- DOWN PAYMENT (FOR PENDING TRANSACTIONS) --}}
        <div class="mb-3">
            <label class="block text-xs font-semibold text-neutral-700 mb-1">Down Payment (Optional, for Pending)</label>
            <input type="number" step="0.01" name="down_payment" placeholder="0.00"
                   class="w-full border border-neutral-200 rounded-lg py-2 px-3 text-sm">
        </div>

        {{-- SHIPPING FIELDS GO HERE (NEXT STEP) --}}
        @include('pos.partials.shipping-fields')

        {{-- TOTAL --}}
        <div class="border-t border-neutral-200 pt-3 mb-4">
            <div class="flex justify-between text-base font-bold text-neutral-900">
                <span>Total</span>
                <span class="text-brand-yellow-deep">&#8369; {{ number_format($total, 2) }}</span>
            </div>
        </div>

        {{-- CHECKOUT BUTTONS --}}
        <div class="flex gap-2">
            <button type="submit" name="checkout" value="pending"
                    class="flex-1 bg-neutral-100 text-neutral-700 font-semibold py-3 rounded-lg text-sm">
                Save Pending
            </button>
            <button type="submit" name="checkout" value="completed"
                    class="flex-1 bg-brand-yellow text-brand-black font-semibold py-3 rounded-lg text-sm">
                Proceed to Payment
            </button>
        </div>

    </form>
</div>