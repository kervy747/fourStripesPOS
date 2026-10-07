@php
    $product = $product ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    {{-- ITEM CODE --}}
    <div>
        <label class="block text-sm font-semibold text-neutral-900 mb-1 font-body">Item Code</label>
        @if($product)
            <input type="text" value="{{ $product->item_code }}" disabled
                   class="w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body text-neutral-500">
        @else
            <div class="w-full border border-dashed border-neutral-200 rounded-lg py-3 px-4 text-sm font-body text-neutral-500">
                Auto-generated based on category
            </div>
        @endif
    </div>

    {{-- ITEM NAME --}}
    <div>
        <x-input label="Item Name" name="name" placeholder="e.g. Cacao Grinder" :value="$product->name ?? ''" />
        <x-error name="name" />
    </div>

    {{-- CATEGORY --}}
    <div>
        <label class="block text-sm font-semibold text-neutral-900 mb-1 font-body">Category</label>
        @if($product)
            <input type="text" value="{{ ucfirst($product->category) }}" disabled
                class="w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body text-neutral-500">
            <p class="text-xs text-neutral-400 mt-1 font-body">Category cannot be changed after an item is created.</p>
        @else
            <select name="category" class="w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body focus:outline-none focus:ring-2 focus:ring-brand-yellow">
                <option value="">Select category</option>
                <option value="machines" @selected(old('category') === 'machines')>Machines</option>
                <option value="tools" @selected(old('category') === 'tools')>Tools</option>
                <option value="accessories" @selected(old('category') === 'accessories')>Accessories</option>
            </select>
            <x-error name="category" />
        @endif
    </div>

    {{-- STOCK QUANTITY --}}
    <div>
        <x-input label="Stock Quantity" name="quantity" type="number" placeholder="0" :value="$product->quantity ?? ''" />
        <x-error name="quantity" />
    </div>

    {{-- REORDER LEVEL --}}
    <div>
        <x-input label="Reorder Level" name="reorder_level" type="number" placeholder="5" :value="$product->reorder_level ?? ''" />
        <x-error name="reorder_level" />
    </div>

    {{-- UNIT COST --}}
    <div>
        <x-input label="Unit Cost" name="unit_cost" type="number" placeholder="0.00" :value="$product->unit_cost ?? ''" />
        <x-error name="unit_cost" />
    </div>

    {{-- SELLING PRICE --}}
    <div>
        <x-input label="Selling Price" name="price" type="number" placeholder="0.00" :value="$product->price ?? ''" />
        <x-error name="price" />
    </div>

</div>

{{-- DESCRIPTION --}}
<div class="mt-4">
    <label class="block text-sm font-semibold text-neutral-900 mb-1 font-body">Description (Optional)</label>
    <textarea name="description" rows="3" class="w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body focus:outline-none focus:ring-2 focus:ring-brand-yellow">{{ old('description', $product->description ?? '') }}</textarea>
    <x-error name="description" />
</div>