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

                    {{-- HIDDEN REAL INPUT --}}
                    <input
                        type="file"
                        name="backup_file"
                        id="backup_file"
                        accept=".sql"
                        class="hidden"
                        onchange="
                            var name = this.files[0] ? this.files[0].name : null;
                            var placeholder = document.getElementById('file-placeholder');
                            var preview = document.getElementById('file-preview');
                            var filename = document.getElementById('file-name');
                            if (name) {
                                placeholder.classList.add('hidden');
                                preview.classList.remove('hidden');
                                preview.classList.add('flex');
                                filename.textContent = name;
                            } else {
                                placeholder.classList.remove('hidden');
                                preview.classList.add('hidden');
                                preview.classList.remove('flex');
                            }
                        "
                    >

                    {{-- STYLED TRIGGER --}}
                    <label for="backup_file"
                        class="flex items-center gap-3 w-full border border-neutral-200 bg-neutral-100 rounded-lg py-3 px-4 cursor-pointer hover:bg-neutral-200 transition">

                        {{-- PLACEHOLDER STATE --}}
                        <span id="file-placeholder" class="flex items-center gap-2 text-sm text-neutral-500 font-body">
                            <img src="{{ asset('images/icons/black-file.svg') }}" class="w-4 h-4 opacity-40" alt="">
                            Choose a .sql file…
                        </span>

                        {{-- SELECTED STATE --}}
                        <span id="file-preview" class="hidden items-center gap-2 text-sm text-neutral-900 font-body font-semibold"
                            <img src="{{ asset('images/icons/black-file.svg') }}" class="w-4 h-4" alt="">
                            <span id="file-name"></span>
                        </span>

                    </label>

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