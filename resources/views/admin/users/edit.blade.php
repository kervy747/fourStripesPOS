<x-layout active="users">

    {{-- PAGE HEADER --}}
    <x-page-header title="Edit User" subtitle="Update account details for {{ $user->full_name }}" />

    <main class="p-6 flex-1">

        {{-- USER FORM --}}
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="bg-neutral-0 rounded-xl p-6 shadow-sm max-w-3xl space-y-4">
            @csrf
            @method('PUT')

            @include('admin.users._form', ['user' => $user])

            {{-- BUTTONS --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('users.index') }}"
                   class="font-heading font-bold py-3 px-6 rounded-lg bg-neutral-200 text-neutral-900 hover:bg-neutral-100">
                    Cancel
                </a>
                <x-button type="submit" variant="primary">Update User</x-button>
            </div>

        </form>

    </main>

</x-layout>
