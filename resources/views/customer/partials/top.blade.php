{{-- TOP SPENDERS BADGE --}}
@if ($topSpenderIds->contains($customer->id))
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-heading bg-brand-yellow-tint text-brand-yellow-deep">
        ★ Top Spender
    </span>
@endif  