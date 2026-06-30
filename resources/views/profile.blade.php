@extends('layouts.app')

@section('content')
<div x-data="profileData()">
    <x-headers.page-header title="Meus Agendamentos" subtitle="Meus agendamentos" />

    <div class="bg-white rounded-[10px] shadow-sm p-6 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-text-muted mb-1">Nome</label>
                <p class="text-text-main font-medium">{{ auth()->user()?->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-muted mb-1">E-mail</label>
                <p class="text-text-main font-medium">{{ auth()->user()?->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-muted mb-1">Perfil</label>
                <p class="text-text-main font-medium">{{ auth()->user()?->isAdmin() ? 'Administrador' : 'Atendente' }}</p>
            </div>
        </div>
    </div>

    <x-tables.data-table
        :headers="[
            ['label' => 'Cliente'],
            ['label' => 'E-mail'],
            ['label' => 'Data'],
            ['label' => 'Horário'],
            ['label' => 'Status'],
        ]"
        items="appointments" loading="loading" emptyMessage="Nenhum agendamento encontrado.">
        <template x-for="appt in appointments" :key="appt.public_id">
            <tr class="hover:bg-white/80 transition-colors duration-200">
                <td class="px-4 py-3 font-medium" x-text="appt.client_name"></td>
                <td class="px-4 py-3 text-text-muted" x-text="appt.client_email || '-'"></td>
                <td class="px-4 py-3 text-text-main" x-text="appt.date"></td>
                <td class="px-4 py-3 text-text-main" x-text="appt.start_time + ' - ' + appt.end_time"></td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                          :class="statusClass(appt.status)"
                          x-text="statusLabel(appt.status)"></span>
                </td>
            </tr>
        </template>
    </x-tables.data-table>
</div>
@endsection
