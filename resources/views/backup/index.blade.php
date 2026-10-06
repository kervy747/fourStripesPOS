<x-layout :active="'backup'">

    <x-page-header title="Backup" subtitle="Create and restore database backups" />

    <main class="p-6 flex-1 space-y-6">

        {{-- FLASH MESSAGES --}}
        <x-alert />

        {{-- CREATE BACKUP CARD --}}
        <div class="bg-neutral-0 rounded-xl p-6 shadow-sm max-w-2xl">

            <h2 class="text-base font-heading font-bold text-neutral-900 mb-1">Create Backup</h2>
            <p class="text-sm text-neutral-600 mb-4">Download a full copy of the database as a <span class="font-semibold">.sql</span> file. Store it somewhere safe.</p>

            <form method="POST" action="{{ route('backup.store') }}">
                @csrf
                <x-button type="submit" variant="primary">Download Backup</x-button>
            </form>

        </div>

        {{-- RESTORE BACKUP CARD --}}
        <div class="bg-neutral-0 rounded-xl p-6 shadow-sm max-w-2xl">

            <h2 class="text-base font-heading font-bold text-neutral-900 mb-1">Restore Backup</h2>
            <p class="text-sm text-neutral-600 mb-4">Upload a <span class="font-semibold">.sql</span> file previously created by this system to restore the database.</p>

            <form method="POST" action="{{ route('backup.restore') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- FILE INPUT --}}
                <div>
                    <label for="backup_file" class="block text-sm font-semibold text-neutral-900 mb-1 font-body">
                        Select Backup File
                    </label>
                    <input
                        type="file"
                        name="backup_file"
                        id="backup_file"
                        accept=".sql"
                        class="w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 text-sm font-body text-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-yellow"
                    >
                    <x-error name="backup_file" />
                </div>

                {{-- WARNING --}}
                <p class="text-sm font-semibold text-danger">
                    ⚠ Restoring replaces all current data. This cannot be undone.
                </p>

                <x-button type="submit" variant="secondary">Restore Backup</x-button>

            </form>

        </div>

    </main>

</x-layout>