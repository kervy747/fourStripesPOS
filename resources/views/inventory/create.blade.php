<x-layout active="inventory">

    <x-page-header title="Add Item" subtitle="Add a new product to inventory" />

    <main class="p-6 flex-1">
        <div class="bg-neutral-0 rounded-xl p-6 shadow-sm max-w-3xl">

            <form method="POST" action="{{ route('inventory.store') }}">
                @csrf

                @include('inventory.form')

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('inventory.index') }}" class="px-4 py-3 rounded-lg text-sm font-semibold bg-neutral-100 text-neutral-700">
                        Cancel
                    </a>
                    <x-button type="submit">
                        Save Item
                    </x-button>
                </div>
            </form>

        </div>
    </main>

</x-layout>