<x-layout active="customers">

    {{-- PAGE HEADER --}}
    <x-page-header title="Customers" subtitle="View customer profiles and spending activity" />

    <main class="p-6 flex-1">

        {{-- STATS --}}
        @include('customer.partials.stats')

        {{-- FILTER BAR --}}
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <form method="GET" action="{{ route('customer.index') }}">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search by name or phone..."
                        class="flex-1 rounded-lg border border-neutral-200 px-4 py-2 text-sm font-body text-neutral-700 focus:outline-none focus:ring-2 focus:ring-brand-yellow"
                    />
                    <button type="submit" class="px-5 py-2 bg-brand-yellow text-brand-black text-sm font-heading rounded-lg hover:bg-brand-yellow-deep hover:text-white transition">
                        Search
                    </button>
                    @if ($search)
                        <a href="{{ route('customer.index') }}" class="px-5 py-2 bg-neutral-100 text-neutral-600 text-sm font-heading rounded-lg hover:bg-neutral-200 transition text-center">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- CUSTOMER TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm font-body">
                <thead class="bg-neutral-100 text-neutral-500 text-xs font-heading uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Phone</th>
                        <th class="px-4 py-3 text-left">Address</th>
                        <th class="px-4 py-3 text-center">Total Orders</th>
                        <th class="px-4 py-3 text-right">Total Spent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($customers as $customer)
                        <tr
                            onclick="window.location='{{ route('customer.show', $customer) }}'"
                            class="hover:bg-neutral-50 cursor-pointer transition"
                        >
                            {{-- NAME + TOP SPENDER BADGE --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-heading text-neutral-900">{{ $customer->name }}</span>
                                    @include('customer.partials.top')
                                </div>
                            </td>

                            {{-- PHONE --}}
                            <td class="px-4 py-3 text-neutral-600">
                                {{ $customer->phone_number ?? '—' }}
                            </td>

                            {{-- ADDRESS --}}
                            <td class="px-4 py-3 text-neutral-600">
                                {{ $customer->address ?? '—' }}
                            </td>

                            {{-- TOTAL ORDERS --}}
                            <td class="px-4 py-3 text-center text-neutral-700">
                                {{ number_format($customer->sales_count) }}
                            </td>

                            {{-- TOTAL SPENT --}}
                            <td class="px-4 py-3 text-right font-heading text-neutral-900">
                                ₱{{ number_format($customer->sales_sum_total ?? 0, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-neutral-400 font-body">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $customers->links() }}
        </div>

    </main>

</x-layout>