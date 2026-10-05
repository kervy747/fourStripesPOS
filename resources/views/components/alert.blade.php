{{-- SUCCESS MESSAGE --}}
@if(session('success'))
    <div class="mb-4 bg-success-tint text-success text-sm font-semibold rounded-lg px-4 py-3">
        {{ session('success') }}
    </div>
@endif

{{-- ERROR MESSAGE --}}
@if(session('error'))
    <div class="mb-4 bg-danger-tint text-danger text-sm font-semibold rounded-lg px-4 py-3">
        {{ session('error') }}
    </div>
@endif
