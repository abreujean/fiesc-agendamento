@props([
    'title' => '',
    'subtitle' => '',
    'actionLabel' => null,
    'actionHref' => null,
    'showAction' => null,
])

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-primary">{{ $title }}</h2>
        <p class="text-text-muted text-sm">{{ $subtitle }}</p>
    </div>
    @if($actionLabel && $actionHref)
        <a href="{{ $actionHref }}"
           @if($showAction) x-show="{{ $showAction }}" @endif
           class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-primary text-white text-sm rounded-[10px] hover:bg-primary-light cursor-pointer transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            {{ $actionLabel }}
        </a>
    @endif
</div>
