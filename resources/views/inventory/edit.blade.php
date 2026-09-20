<x-layout active="inventory">

    <x-page-header title="Edit Item" subtitle="Update product details" />

    <main class="p-6 flex-1">
        <div class="bg-neutral-0 rounded-xl p-6 shadow-sm max-w-3xl">

            <form method="POST" action="{{ route('inventory.update', $product->id) }}">
                @csrf
                @method('PUT')

                @include('inventory.form', ['product' => $product])

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('inventory.index') }}" class="px-4 py-3 rounded-lg text-sm font-semibold bg-neutral-100 text-neutral-700">
                        Cancel
                    </a>
                    <x-button type="submit">
                        Update Item
                    </x-button>
                </div>
            </form>

        </div>
    </main>

</x-layout>