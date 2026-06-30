@extends('layouts.app')

@section('content')
<div x-data="availabilitiesData()">
    <x-headers.page-header title="Disponibilidade" subtitle="Janelas de horários dos atendentes"
        actionLabel="Nova Disponibilidade" actionHref="/availabilities/create" />

    <x-tables.data-table
        :headers="[
            ['label' => 'Atendente'],
            ['label' => 'Dia da Semana'],
            ['label' => 'Hora Início'],
            ['label' => 'Hora Fim'],
            ['label' => 'Status'],
            ['label' => 'Ações', 'class' => 'text-right px-4 py-3 font-medium text-text-muted'],
        ]"
        items="availabilities" loading="loading" emptyMessage="Nenhuma disponibilidade cadastrada.">
        <template x-for="item in availabilities" :key="item.public_id">
            <tr class="hover:bg-white/80 transition-colors duration-200">
                <td class="px-4 py-3 font-medium" x-text="item.user?.name || '-'"></td>
                <td class="px-4 py-3 text-text-muted" x-text="getDayLabel(item.day_of_week)"></td>
                <td class="px-4 py-3 text-text-muted" x-text="item.start_time"></td>
                <td class="px-4 py-3 text-text-muted" x-text="item.end_time"></td>
                <td class="px-4 py-3">
                    <x-badges.status
                        condition="item.is_active"
                        trueLabel="Ativo" falseLabel="Inativo"
                        trueClass="bg-primary/10 text-primary" falseClass="bg-bg-main text-text-muted" />
                </td>
                <td class="px-4 py-3 text-right">
                    <a :href="`/availabilities/${item.public_id}/edit`"
                       class="inline-flex items-center gap-1 text-primary hover:text-primary-light text-sm font-medium cursor-pointer transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                        Editar
                    </a>
                </td>
            </tr>
        </template>
    </x-tables.data-table>
</div>
@endsection
