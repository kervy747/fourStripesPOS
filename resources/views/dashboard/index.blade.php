<x-layout active="dashboard">

    {{-- PAGE HEADER --}}
    <x-page-header title="Dashboard" subtitle="Welcome to your dashboard"></x-page-header>

    <main class="p-6 flex-1">

        {{-- STAT CARDS --}}
        @include('inventory.stat-card')

        {{-- OUT OF STOCK + LOW STOCK LISTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

            {{-- OUT OF STOCK LIST --}}
            <div class="bg-neutral-0 rounded-xl shadow-sm p-4">
                <div class="text-md font-semibold text-brand-black mb-3">Out of Stock</div>

                @if($outOfStockProducts->isEmpty())
                    <div class="bg-success-tint text-success text-sm font-semibold px-4 py-3 rounded-lg">
                        All items are currently in stock.
                    </div>
                @else
                    <div class="rounded-xl shadow-[0_0_1px_0px_black] overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2 bg-white border-b border-neutral-200 text-xs font-semibold text-neutral-500">
                            <span>Product Name</span>
                            <span>Category</span>
                        </div>
                        @foreach($outOfStockProducts as $product)
                            <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-100 bg-white">
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900">{{ $product->name }}</p>
                                    <p class="text-xs text-neutral-400">{{ $product->item_code }}</p>
                                </div>
                                <span class="text-xs font-semibold text-neutral-500 capitalize">{{ $product->category }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        {{ $outOfStockProducts->links() }}
                    </div>
                @endif
            </div>

            {{-- LOW STOCK LIST --}}
            <div class="bg-neutral-0 rounded-xl shadow-sm p-4">
                <div class="text-md font-semibold text-brand-black mb-3">Low Stock</div>

                @if($lowStockProducts->isEmpty())
                    <div class="bg-success-tint text-success text-sm font-semibold px-4 py-3 rounded-lg">
                        No items are running low.
                    </div>
                @else
                    <div class="rounded-xl shadow-[0_0_1px_0px_black] overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2 bg-white border-b border-neutral-200 text-xs font-semibold text-neutral-500">
                            <span>Product Name</span>
                            <span>Qty Left</span>
                        </div>
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-100 bg-white">
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900">{{ $product->name }}</p>
                                    <p class="text-xs text-neutral-400">{{ $product->item_code }}</p>
                                </div>
                                <span class="bg-warning-tint text-warning text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $product->quantity }} left
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        {{ $lowStockProducts->links() }}
                    </div>
                @endif
            </div>

        </div>

        {{-- TOP PRODUCTS BAR CHART --}}
        <div class="bg-neutral-0 rounded-xl shadow-sm p-4">

            {{-- CHART HEADER + FILTER --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div class="text-md font-semibold text-brand-black">Top Products</div>

                {{-- MONTH / YEAR FILTER --}}
                <form method="GET" action="{{ route('dashboard.index') }}" class="flex items-center gap-2">
                    <select name="month" onchange="this.form.submit()"
                        class="text-sm border border-neutral-300 rounded-lg px-3 py-2 focus:outline-none focus:border-black">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endforeach
                    </select>

                    <select name="year" onchange="this.form.submit()"
                        class="text-sm border border-neutral-300 rounded-lg px-3 py-2 focus:outline-none focus:border-black">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- CHART --}}
            @if($topProducts->isEmpty())
                <div class="flex items-center justify-center h-48 text-sm text-neutral-500">
                    No sales data for {{ \Carbon\Carbon::create()->month($selectedMonth)->format('F') }} {{ $selectedYear }}.
                </div>
            @else
                <div class="relative h-80">
                    <canvas id="topProductsChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
                <script>
                    const labels   = @json($topProducts->pluck('item_name'));
                    const units    = @json($topProducts->pluck('total_units'));
                    const revenue  = @json($topProducts->pluck('total_revenue'));

                    const ctx = document.getElementById('topProductsChart').getContext('2d');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Units Sold',
                                    data: units,
                                    backgroundColor: '#FCBC18',
                                    borderRadius: 6,
                                    yAxisID: 'yUnits',
                                },
                                {
                                    label: 'Revenue (₱)',
                                    data: revenue,
                                    backgroundColor: '#0F1113',
                                    borderRadius: 6,
                                    yAxisID: 'yRevenue',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        font: { family: 'Poppins', size: 12 }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (context.dataset.label === 'Revenue (₱)') {
                                                return ' ₱ ' + Number(context.raw).toLocaleString('en-PH', { minimumFractionDigits: 2 });
                                            }
                                            return ' ' + context.raw + ' units';
                                        }
                                    }
                                }
                            },
                            scales: {
                                yUnits: {
                                    type: 'linear',
                                    position: 'left',
                                    ticks: {
                                        font: { family: 'Poppins', size: 11 },
                                        stepSize: 1,
                                    },
                                    title: {
                                        display: true,
                                        text: 'Units Sold',
                                        font: { family: 'Poppins', size: 11 }
                                    }
                                },
                                yRevenue: {
                                    type: 'linear',
                                    position: 'right',
                                    grid: { drawOnChartArea: false },
                                    ticks: {
                                        font: { family: 'Poppins', size: 11 },
                                        callback: function(value) {
                                            return '₱ ' + Number(value).toLocaleString('en-PH');
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Revenue (₱)',
                                        font: { family: 'Poppins', size: 11 }
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: { family: 'Poppins', size: 11 },
                                        maxRotation: 30,
                                    }
                                }
                            }
                        }
                    });
                </script>
            @endif

        </div>

    </main>

</x-layout>