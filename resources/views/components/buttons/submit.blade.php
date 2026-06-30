@props([
    'label' => 'Salvar',
    'loadingLabel' => 'Salvando...',
    'color' => 'primary',
    'fullWidth' => false,
    'showSpinner' => false,
    'type' => 'submit',
    'onclick' => null,
])

@php
    $colorMap = [
        'primary' => 'bg-primary hover:bg-primary-light',
        'secondary' => 'bg-secondary hover:bg-secondary-light',
        'accent' => 'bg-accent hover:bg-accent/90',
    ];
    $colorClasses = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<button type="{{ $type }}"
        @if($onclick) @click="{{ $onclick }}" @endif
        :disabled="loading"
        class="{{ $fullWidth ? 'w-full flex items-center justify-center' : 'inline-flex items-center' }} gap-1.5 px-4 py-2.5 {{ $colorClasses }} text-white rounded-[10px] text-sm font-medium disabled:opacity-50 cursor-pointer transition-colors duration-200">
    @if($showSpinner)
        <svg x-show="loading" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
    @endif
    <span x-show="!loading">{{ $label }}</span>
    <span x-show="loading">{{ $loadingLabel }}</span>
</button>
