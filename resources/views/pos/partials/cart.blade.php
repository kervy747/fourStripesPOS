{{-- RIGHT: CURRENT TRANSACTION --}}
<div class="w-full lg:w-96 bg-neutral-0 rounded-xl shadow-sm p-4 flex flex-col h-[calc(100vh-180px)] sticky top-6">

    <h2 class="font-heading font-bold text-lg text-neutral-900 mb-4">Current Transaction</h2>

    <form method="POST" action="{{ route('pos.cart.update') }}" class="flex flex-col flex-1 min-h-0">
        @csrf

        {{-- CART ITEMS (SCROLLS INTERNALLY, KEEPS PANEL HEIGHT FIXED) --}}
        <div class="flex-1 overflow-y-auto space-y-4 mb-4">
            @forelse($cart as $item)
                <div class="border-b border-neutral-100 pb-3">

                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-neutral-900 text-sm">{{ $item['name'] }}</p>
                            <p class="text-xs text-neutral-500">{{ $item['item_code'] }}</p>
                        </div>
                        <button type="submit" name="remove" value="{{ $item['product_id'] }}">
                            <img src="{{ asset('images/icons/red-remove.svg') }}" class="w-4 h-4" alt="Remove">
                        </button>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-2">
                            <button type="submit" name="decrease" value="{{ $item['product_id'] }}"
                                    class="w-7 h-7 rounded bg-neutral-100 text-neutral-700 text-sm font-bold">-</button>
                            <span class="text-sm w-6 text-center">{{ $item['quantity'] }}</span>
                            <button type="submit" name="increase" value="{{ $item['product_id'] }}"
                                    class="w-7 h-7 rounded bg-neutral-100 text-neutral-700 text-sm font-bold">+</button>
                        </div>
                        <span class="text-sm font-semibold text-neutral-900">
                            &#8369; {{ number_format($item['price'] * $item['quantity'], 2) }}
                        </span>
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

        {{-- CUSTOMER NAME --}}
        <div class="mb-3">
            <label class="block text-xs font-semibold text-neutral-700 mb-1">Customer Name</label>
            <input type="text" name="customer_name" value="{{ $customerName }}" placeholder="Enter customer name..."
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