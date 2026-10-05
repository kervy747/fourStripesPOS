<x-layout active="users">

    {{-- PAGE HEADER --}}
    <x-page-header title="Add User" subtitle="Create a new admin or staff account" />

    <main class="p-6 flex-1">

        {{-- USER FORM --}}
        <form method="POST" action="{{ route('users.store') }}" class="bg-neutral-0 rounded-xl p-6 shadow-sm max-w-3xl space-y-4">
            @csrf

            @include('admin.users._form', ['user' => null])

            {{-- BUTTONS --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('users.index') }}"
                   class="font-heading font-bold py-3 px-6 rounded-lg bg-neutral-200 text-neutral-900 hover:bg-neutral-100">
                    Cancel
                </a>
                <x-button type="submit" variant="primary">Save User</x-button>
            </div>

        </form>

    </main>

</x-layout>
