<x-layout active="users">

    {{-- PAGE HEADER --}}
    <x-page-header title="User" subtitle="Manage admin and staff accounts" />

    <main class="p-6 flex-1">

        {{-- FLASH MESSAGES --}}
        <x-alert />

        {{-- ADD USER BUTTON --}}
        <div class="flex justify-end mb-4">
            <a href="{{ route('users.create') }}"
               class="bg-brand-yellow text-brand-black font-heading font-bold px-4 py-2 rounded-lg text-sm">
                + Add User
            </a>
        </div>

        {{-- FILTERS + SEARCH --}}
        <form method="GET" action="{{ route('users.index') }}" class="bg-neutral-0 rounded-xl p-4 shadow-sm mb-4 flex flex-wrap items-end gap-4">

            {{-- ROLE --}}
            <div>
                <label class="block text-xs font-semibold text-neutral-700 mb-1">Role</label>
                <select name="role" class="border border-neutral-200 rounded-lg py-2 px-3 text-sm">
                    <option value="">All Roles</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="staff" @selected(request('role') === 'staff')>Staff</option>
                </select>
            </div>

            {{-- SEARCH --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-neutral-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
                       class="w-full border border-neutral-200 rounded-lg py-2 px-3 text-sm">
            </div>

            {{-- BUTTONS --}}
            <div class="flex gap-2">
                <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold bg-neutral-100 text-neutral-700">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold bg-brand-yellow text-brand-black">
                    Apply Filter
                </button>
            </div>

        </form>

        {{-- USERS TABLE --}}
        <div class="bg-neutral-0 rounded-xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-neutral-200 text-left text-neutral-600">
                    <tr>
                        <th class="p-3">Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Phone Number</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-neutral-100">
                            <td class="p-3 font-semibold text-neutral-900">{{ $user->full_name }}</td>
                            <td class="p-3 text-neutral-600">{{ $user->email }}</td>
                            <td class="p-3 text-neutral-600">{{ $user->phone_number }}</td>

                            {{-- ROLE BADGE --}}
                            <td class="p-3">
                                @if($user->isAdmin())
                                    <span class="bg-info-tint text-info text-xs font-semibold px-3 py-1 rounded-full">Admin</span>
                                @else
                                    <span class="bg-neutral-100 text-neutral-700 text-xs font-semibold px-3 py-1 rounded-full">Staff</span>
                                @endif
                            </td>

                            {{-- STATUS BADGE --}}
                            <td class="p-3">
                                @if($user->is_active)
                                    <span class="bg-success-tint text-success text-xs font-semibold px-3 py-1 rounded-full">Active</span>
                                @else
                                    <span class="bg-danger-tint text-danger text-xs font-semibold px-3 py-1 rounded-full">Inactive</span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="p-3">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('users.edit', $user->id) }}">
                                        <img src="{{ asset('images/icons/grey-edit.svg') }}" class="w-4 h-4" alt="Edit">
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.toggle-status', $user->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-semibold {{ $user->is_active ? 'text-danger' : 'text-success' }}">
                                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-neutral-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION (DEFAULT LARAVEL TAILWIND STYLE) --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </main>

</x-layout>
