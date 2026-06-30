@props([
    'headers' => [],
    'items' => 'items',
    'loading' => 'loading',
    'emptyMessage' => 'Nenhum registro encontrado.',
    'loadingMessage' => 'Carregando...',
])

<div class="bg-white rounded-[10px] shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bg-main border-b border-border-main">
            <tr>
                @foreach($headers as $header)
                    <th class="{{ $header['class'] ?? 'text-left px-4 py-3 font-medium text-text-muted' }}">{{ $header['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-border-main">
            {{ $slot }}
        </tbody>
    </table>

    <x-states.list-state :items="$items" :loading="$loading" :emptyMessage="$emptyMessage" :loadingMessage="$loadingMessage" />
</div>
