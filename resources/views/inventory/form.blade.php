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
        <select name="category" class="w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body focus:outline-none focus:ring-2 focus:ring-brand-yellow">
            <option value="">Select category</option>
            <option value="machines" @selected(old('category', $product->category ?? '') === 'machines')>Machines</option>
            <option value="tools" @selected(old('category', $product->category ?? '') === 'tools')>Tools</option>
            <option value="accessories" @selected(old('category', $product->category ?? '') === 'accessories')>Accessories</option>
        </select>
        <x-error name="category" />
    </div>

    {{-- UNIT --}}
    <div>
        <x-input label="Unit" name="unit" placeholder="e.g. unit, piece, pair" :value="$product->unit ?? ''" />
        <x-error name="unit" />
    </div>

    {{-- STOCK QUANTITY --}}
    <div>
        <x-input label="Stock Quantity" name="quantity" type="number" placeholder="0" :value="$product->quantity ?? ''" />
        <x-error name="quantity" />
    </div>

    {{-- STANDARD LEVEL --}}
    <div>
        <x-input label="Standard Level" name="standard_level" type="number" placeholder="5" :value="$product->standard_level ?? ''" />
        <x-error name="standard_level" />
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

    {{-- WEIGHT --}}
    <div>
        <x-input label="Weight (kg)" name="weight" type="number" placeholder="For shipping calculation" :value="$product->weight ?? ''" />
        <x-error name="weight" />
    </div>

    {{-- WARRANTY --}}
    <div class="flex items-center gap-2 mt-6 md:mt-7">
        <input type="checkbox" name="has_warranty" id="has_warranty" value="1"
               @checked(old('has_warranty', isset($product) && $product->warranty_months) )
               class="rounded border-neutral-400">
        <label for="has_warranty" class="text-sm font-body text-neutral-900">
            Motor Part (1 Month Warranty)
        </label>
        <x-error name="has_warranty" />
    </div>

</div>

{{-- DESCRIPTION --}}
<div class="mt-4">
    <label class="block text-sm font-semibold text-neutral-900 mb-1 font-body">Description (Optional)</label>
    <textarea name="description" rows="3" class="w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body focus:outline-none focus:ring-2 focus:ring-brand-yellow">{{ old('description', $product->description ?? '') }}</textarea>
    <x-error name="description" />
</div>