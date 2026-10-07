{{-- RIGHT: CURRENT TRANSACTION --}}
<div class="sticky right-0 w-full lg:w-130 bg-neutral-0 rounded-xl shadow-sm p-4 flex flex-col h-[calc(100vh-130px)] sticky top-6">

    <h2 class="font-heading font-bold text-lg text-neutral-900 mb-4">Current Transaction</h2>

    <form id="cart-form" method="POST" action="{{ route('pos.cart.update') }}" class="flex flex-col flex-1 min-h-0">
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
                                class="w-7 h-7 rounded bg-neutral-100 text-neutral-700 text-sm font-bold">
                                -
                            </button>

                            <input
                                type="number"
                                name="quantity[{{ $item['product_id'] }}]"
                                value="{{ $item['quantity'] }}"
                                min="1"
                                class="w-12 text-center text-sm border border-neutral-200 rounded
                                    [appearance:textfield]
                                    [&::-webkit-outer-spin-button]:appearance-none
                                    [&::-webkit-inner-spin-button]:appearance-none
                                    focus:outline-none focus:ring-0"
                            >

                            <button type="submit" name="increase" value="{{ $item['product_id'] }}"
                                class="w-7 h-7 rounded bg-neutral-100 text-neutral-700 text-sm font-bold">
                                +
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

        {{-- INFO / ERROR / SUCCESS MESSAGES --}}
        @if(session('success'))
            <div class="bg-success-tint text-success p-3 rounded-lg mb-4 text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>

                @if(session('receipt_url'))
                    <a href="{{ session('receipt_url') }}" target="_blank"
                    class="ml-4 font-semibold text-brand-yellow-deep underline">
                        View Receipt
                    </a>
                @endif
            </div>
        @endif
        @if(session('info'))
            <div class="bg-info-tint text-info p-3 rounded-lg mb-4 text-sm">{{ session('info') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-danger-tint text-danger p-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
        @endif
        
        {{-- TOTAL --}}
        <div class="border-t border-neutral-200 pt-3 mb-4">
            <div class="flex justify-between text-base font-bold text-neutral-900">
                <span>Total</span>
                <span class="text-brand-yellow-deep">&#8369; {{ number_format($total, 2) }}</span>
            </div>
        </div>

        {{-- CASH RECEIVED --}}
        <div class="mb-3">
            <label class="block text-xs font-semibold text-neutral-700 mb-1">
                Cash Received
            </label>

            <div class="flex gap-2">
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="cash_received"
                    id="cash_received"
                    value="{{ $cashReceived }}"
                    placeholder="0.00"
                    class="flex-1 border border-neutral-200 rounded-lg py-2 px-3 text-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                    oninput="calculateChange()"
                >
            </div>

            <x-error name="cash_received" />
        </div>

        {{-- CHANGE --}}
        <div class="mb-3 flex justify-between items-center bg-neutral-100 rounded-lg px-3 py-2">
            <span class="text-xs font-semibold text-neutral-700">Change</span>

            <span id="change_amount" class="text-sm font-bold text-neutral-900">
                ₱ {{ number_format($change, 2) }}
            </span>
        </div>

        <script>
            function calculateChange() {
                const cashReceived = parseFloat(document.getElementById('cash_received').value) || 0;
                const total = {{ $total }};

                const change = cashReceived - total;

                document.getElementById('change_amount').textContent =
                    '₱ ' + change.toFixed(2);
            }
        </script>

        {{-- CHECKOUT BUTTONS --}}
        <div class="flex">
            <button type="submit" name="checkout" value="completed"
                    class="flex-1 bg-brand-yellow text-brand-black font-semibold py-3 rounded-lg text-sm">
                Proceed to Payment
            </button>
        </div>

    </form>
</div>