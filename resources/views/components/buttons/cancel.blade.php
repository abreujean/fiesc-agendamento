@props([
    'label' => 'Cancelar',
    'href' => null,
    'onclick' => null,
])

@if($href)
    <a href="{{ $href }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm text-text-main bg-bg-main rounded-[10px] hover:bg-border-main cursor-pointer transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        {{ $label }}
    </a>
@else
    <button type="button"
            @if($onclick) @click="{{ $onclick }}" @endif
            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm text-text-main bg-bg-main rounded-[10px] hover:bg-border-main cursor-pointer transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        {{ $label }}
    </button>
@endif
