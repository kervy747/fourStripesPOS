<x-layout active="audit-log">
    <x-page-header title="Audit Log" subtitle="Record of system activity" />

    <div class="p-6">

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('audit-log.index') }}" class="flex flex-wrap items-end gap-3 mb-6">
            <div>
                <label for="search" class="block mb-1 text-sm font-heading font-semibold text-neutral-700">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Search description"
                    class="w-64 px-3 py-2 text-sm border rounded-lg font-body border-neutral-200 bg-neutral-0">
            </div>

            <div>
                <label for="module" class="block mb-1 text-sm font-heading font-semibold text-neutral-700">Module</label>
                <select id="module" name="module"
                    class="px-3 py-2 text-sm border rounded-lg font-body border-neutral-200 bg-neutral-0">
                    <option value="">All modules</option>
                    <option value="auth" @selected(request('module') == 'auth')>Auth</option>
                    <option value="inventory" @selected(request('module') == 'inventory')>Inventory</option>
                    <option value="sales" @selected(request('module') == 'sales')>Sales</option>
                    <option value="users" @selected(request('module') == 'users')>Users</option>
                </select>
            </div>

            <x-button type="submit" variant="primary">Filter</x-button>
        </form>

        {{-- LOG TABLE --}}
        <div class="overflow-x-auto border rounded-lg border-neutral-200 bg-neutral-0">
            <table class="w-full text-sm text-left font-body">
                <thead class="bg-neutral-100 text-neutral-700 font-heading">
                    <tr>
                        <th class="px-4 py-3">Date &amp; Time</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Module</th>
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        {{-- ACTION BADGE COLOR --}}
                        @php
                            if ($log->action == 'created') {
                                $badge = 'bg-success-tint text-success';
                            } elseif ($log->action == 'updated') {
                                $badge = 'bg-info-tint text-info';
                            } elseif ($log->action == 'deleted') {
                                $badge = 'bg-danger-tint text-danger';
                            } else {
                                $badge = 'bg-neutral-100 text-neutral-700';
                            }
                        @endphp

                        <tr class="border-t border-neutral-200">
                            <td class="px-4 py-3 whitespace-nowrap">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            <td class="px-4 py-3">{{ $log->user->name ?? 'Deleted user' }}</td>
                            <td class="px-4 py-3 capitalize">{{ $log->module }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold capitalize rounded-full {{ $badge }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-neutral-600">
                                No activity found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>
</x-layout>