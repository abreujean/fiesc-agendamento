@props([
    'title' => '',
    'subtitle' => '',
    'backUrl' => '/',
])

<div class="mb-6">
    <h2 class="text-2xl font-bold text-primary mb-1">{{ $title }}</h2>
    <p class="text-text-muted text-sm mb-4">{{ $subtitle }}</p>
    <a href="{{ $backUrl }}" class="inline-flex items-center gap-1 text-primary hover:text-primary-light text-sm cursor-pointer transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Voltar
    </a>
</div>
