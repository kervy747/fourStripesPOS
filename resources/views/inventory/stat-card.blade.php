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