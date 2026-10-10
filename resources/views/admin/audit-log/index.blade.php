<x-layout active="audit-log">

    <x-page-header title="Audit Log" subtitle="Record of system activity" />

    <div class="p-6 space-y-4">

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('audit-log.index') }}"
              class="flex flex-wrap gap-3 items-end">

            <div class="flex flex-col gap-1">
                <label class="text-xs font-heading text-neutral-500 uppercase tracking-wide">
                    Action
                </label>
                <select name="action"
                        class="border border-neutral-200 rounded-lg px-3 py-2 text-sm font-body text-neutral-800 bg-white focus:outline-none focus:ring-2 focus:ring-brand-yellow">
                    <option value="">All Actions</option>
                    <option value="login"        @selected(request('action') === 'login')>Login</option>
                    <option value="logout"       @selected(request('action') === 'logout')>Logout</option>
                    <option value="purchase"     @selected(request('action') === 'purchase')>Purchase</option>
                    <option value="print_report" @selected(request('action') === 'print_report')>Print Report</option>
                    <option value="backup"       @selected(request('action') === 'backup')>Backup</option>
                    <option value="stock_added"  @selected(request('action') === 'stock_added')>Stock Added</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-heading text-neutral-500 uppercase tracking-wide">
                    Date
                </label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="border border-neutral-200 rounded-lg px-3 py-2 text-sm font-body text-neutral-800 bg-white focus:outline-none focus:ring-2 focus:ring-brand-yellow" />
            </div>

            <x-button type="submit" variant="primary">Filter</x-button>

            @if(request()->hasAny(['action', 'date']))
                <a href="{{ route('audit-log.index') }}"
                   class="text-sm font-body text-neutral-500 hover:text-danger self-end pb-2">
                    Clear
                </a>
            @endif

        </form>

        {{-- LOG TABLE --}}
        <div class="bg-white rounded-xl border border-neutral-100 overflow-hidden">
            <table class="w-full text-sm font-body">

                {{-- TABLE HEADER --}}
                <thead class="bg-neutral-950 text-neutral-0">
                    <tr>
                        <th class="px-4 py-3 text-left font-heading text-xs uppercase tracking-wide">#</th>
                        <th class="px-4 py-3 text-left font-heading text-xs uppercase tracking-wide">User</th>
                        <th class="px-4 py-3 text-left font-heading text-xs uppercase tracking-wide">Action</th>
                        <th class="px-4 py-3 text-left font-heading text-xs uppercase tracking-wide">Description</th>
                        <th class="px-4 py-3 text-left font-heading text-xs uppercase tracking-wide">Date & Time</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-neutral-100">

                    @forelse ($logs as $log)
                        <tr class="hover:bg-neutral-50 transition-colors">

                            {{-- ID --}}
                            <td class="px-4 py-3 text-neutral-400">{{ $log->id }}</td>

                            {{-- USER --}}
                            <td class="px-4 py-3">
                                @if ($log->user)
                                    <span class="font-heading text-neutral-800">
                                        {{ $log->user->first_name }} {{ $log->user->last_name }}
                                    </span>
                                    <span class="block text-xs text-neutral-400">{{ $log->user->role }}</span>
                                @else
                                    <span class="text-neutral-400 italic">Deleted user</span>
                                @endif
                            </td>

                            {{-- ACTION BADGE --}}
                            <td class="px-4 py-3">
                                @php
                                    $badgeClass = match($log->action) {
                                        'login'        => 'bg-info-tint text-info',
                                        'logout'       => 'bg-neutral-100 text-neutral-600',
                                        'purchase'     => 'bg-success-tint text-success',
                                        'print_report' => 'bg-brand-yellow-tint text-brand-yellow-deep',
                                        'backup'       => 'bg-warning-tint text-warning',
                                        'stock_added'  => 'bg-success-tint text-success',
                                        default        => 'bg-neutral-100 text-neutral-600',
                                    };

                                    $label = match($log->action) {
                                        'login'        => 'Login',
                                        'logout'       => 'Logout',
                                        'purchase'     => 'Purchase',
                                        'print_report' => 'Print Report',
                                        'backup'       => 'Backup',
                                        'stock_added'  => 'Stock Added',
                                        default        => $log->action,
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-heading {{ $badgeClass }}">
                                    {{ $label }}
                                </span>
                            </td>

                            {{-- DESCRIPTION --}}
                            <td class="px-4 py-3 text-neutral-700">{{ $log->description }}</td>

                            {{-- TIMESTAMP --}}
                            <td class="px-4 py-3 text-neutral-500 whitespace-nowrap">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-neutral-400 font-body">
                                No audit log entries found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        {{ $logs->links() }}

    </div>

</x-layout>