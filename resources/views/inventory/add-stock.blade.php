<x-layout active="inventory">

    <x-page-header title="Add Stock" subtitle="Add new stock to an existing product" />

    <main class="p-6 flex-1">
        <div class="bg-neutral-0 rounded-xl p-6 shadow-sm max-w-lg">

            {{-- PRODUCT SUMMARY --}}
            <div class="bg-neutral-100 rounded-lg p-4 mb-6 flex flex-col gap-1">
                <p class="text-xs text-neutral-500 font-semibold uppercase tracking-wide">Product</p>
                <p class="text-base font-bold text-neutral-900">{{ $product->name }}</p>
                <div class="flex gap-4 mt-1 text-sm text-neutral-600">
                    <span>Code: <span class="font-semibold text-neutral-800">{{ $product->item_code }}</span></span>
                    <span>Current Stock: <span class="font-semibold text-neutral-800">{{ $product->quantity }}</span></span>
                </div>
            </div>

            <form method="POST" action="{{ route('inventory.add-stock', $product->id) }}">
                @csrf
                @method('PATCH')

                {{-- QUANTITY TO ADD --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-neutral-700 mb-1">
                        Quantity to Add <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="quantity_to_add"
                        value="{{ old('quantity_to_add') }}"
                        min="1"
                        placeholder="e.g. 50"
                        class="w-full border border-neutral-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:border-black focus:border-2 @error('quantity_to_add') border-danger @enderror"
                    >
                    @error('quantity_to_add')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-neutral-400 mt-1">New stock total will be: <strong>{{ $product->quantity }}</strong> + your input</p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('inventory.index') }}" class="px-4 py-3 rounded-lg text-sm font-semibold bg-neutral-100 text-neutral-700">
                        Cancel
                    </a>
                    <x-button type="submit">
                        Add Stock
                    </x-button>
                </div>
            </form>

        </div>
    </main>

</x-layout>