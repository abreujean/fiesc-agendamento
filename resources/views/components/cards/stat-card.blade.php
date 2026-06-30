@props([
    'value' => '',
    'label' => '',
    'color' => 'primary',
    'icon' => '',
])

@php
    $borderColor = "border-{$color}";
    $textColor = "text-{$color}";
    $bgColor = "bg-{$color}/10";
@endphp

<div class="bg-white rounded-[10px] shadow-sm p-6 border-l-4 {{ $borderColor }}">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-3xl font-bold text-primary" x-text="{{ $value }}">-</h3>
            <p class="text-text-muted text-sm">{{ $label }}</p>
        </div>
        <div class="w-12 h-12 rounded-[10px] {{ $bgColor }} flex items-center justify-center">
            {!! $icon !!}
        </div>
    </div>
</div>
