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
                onclick="
                    var w = this.closest('.pw-wrap');
                    var f = w.querySelector('input');
                    var img = this.querySelector('img');
                    if (f.type === 'password') {
                        f.type = 'text';
                        img.src = '{{ asset('images/icons/grey-eye-hide.svg') }}';
                    } else {
                        f.type = 'password';
                        img.src = '{{ asset('images/icons/grey-eyes.svg') }}';
                    }
                "
                class="absolute right-3 top-9 focus:outline-none"
            >
                <img src="{{ asset('images/icons/grey-eyes.svg') }}" alt="Toggle password visibility" class="w-5 h-5" />
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
                onclick="
                    var w = this.closest('.pw-wrap');
                    var f = w.querySelector('input');
                    var img = this.querySelector('img');
                    if (f.type === 'password') {
                        f.type = 'text';
                        img.src = '{{ asset('images/icons/grey-eye-hide.svg') }}';
                    } else {
                        f.type = 'password';
                        img.src = '{{ asset('images/icons/grey-eyes.svg') }}';
                    }
                "
                class="absolute right-3 top-9 focus:outline-none"
            >
                <img src="{{ asset('images/icons/grey-eyes.svg') }}" alt="Toggle password visibility" class="w-5 h-5" />
            </button>
        </div>
    </div>
</div>