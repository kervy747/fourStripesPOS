{{-- NAME FIELDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input label="First Name" name="first_name" :value="$user?->first_name" placeholder="Juan" />
        <x-error name="first_name" />
    </div>
    <div>
        <x-input label="Last Name" name="last_name" :value="$user?->last_name" placeholder="Dela Cruz" />
        <x-error name="last_name" />
    </div>
</div>

{{-- CONTACT FIELDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input label="Email" name="email" type="email" :value="$user?->email" placeholder="juan@example.com" />
        <x-error name="email" />
    </div>
    <div>
        <x-input label="Phone Number" name="phone_number" :value="$user?->phone_number" placeholder="09123456789" />
        <x-error name="phone_number" />
    </div>
</div>

{{-- ROLE FIELD --}}
<div>
    <x-select label="Role" name="role" :value="$user?->role" :options="['staff' => 'Staff', 'admin' => 'Admin']" />
    <x-error name="role" />
</div>

{{-- PASSWORD FIELDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        {{-- PW WRAP --}}
        <div class="relative pw-wrap">
            <x-input label="Password" name="password" type="password" :value="session('saved_password')" />
            {{-- VISIBILITY TOGGLE --}}
            <button
                type="button"
                onclick="var w=this.closest('.pw-wrap');var f=w.querySelector('input');f.type=f.type==='password'?'text':'password';"
                class="absolute right-3 top-9 text-neutral-400 hover:text-neutral-700 focus:outline-none"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
        <x-error name="password" />
        @if($user)
            <p class="text-xs text-neutral-600 mt-1">Leave blank to keep the current password.</p>
        @endif
    </div>
    <div>
        {{-- PW WRAP --}}
        <div class="relative pw-wrap">
            <x-input label="Confirm Password" name="password_confirmation" type="password" :value="session('saved_password_confirmation')" />
            {{-- VISIBILITY TOGGLE --}}
            <button
                type="button"
                onclick="var w=this.closest('.pw-wrap');var f=w.querySelector('input');f.type=f.type==='password'?'text':'password';"
                class="absolute right-3 top-9 text-neutral-400 hover:text-neutral-700 focus:outline-none"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>
</div>