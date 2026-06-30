@props([
    'href' => '/',
])

<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-sm text-white/70 hover:bg-white/10 cursor-pointer transition-colors duration-200"
   :class="$data.isActive('{{ $href }}')">
    {{ $slot }}
</a>
