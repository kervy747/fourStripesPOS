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
        <x-input label="Password" name="password" type="password" />
        <x-error name="password" />
        @if($user)
            <p class="text-xs text-neutral-600 mt-1">Leave blank to keep the current password.</p>
        @endif
    </div>
    <div>
        <x-input label="Confirm Password" name="password_confirmation" type="password" />
    </div>
</div>
