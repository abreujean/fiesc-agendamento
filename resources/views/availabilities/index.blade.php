@extends('layouts.app')

@section('content')
<div x-data="availabilitiesData()" x-init="loadData()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Disponibilidade</h2>
            <p class="text-gray-500 text-sm">Janelas de horários dos atendentes</p>
        </div>
        <a href="/availabilities/create"
           class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
            + Nova Disponibilidade
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Atendente</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Dia da Semana</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Hora Início</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Hora Fim</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-600">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-for="item in availabilities" :key="item.public_id">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3" x-text="item.user?.name || '-'"></td>
                        <td class="px-4 py-3" x-text="getDayLabel(item.day_of_week)"></td>
                        <td class="px-4 py-3" x-text="item.start_time"></td>
                        <td class="px-4 py-3" x-text="item.end_time"></td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                  :class="item.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                  x-text="item.is_active ? 'Ativo' : 'Inativo'"></span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a :href="`/availabilities/${item.public_id}/edit`"
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Editar
                            </a>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div x-show="availabilities.length === 0 && !loading" class="text-center py-8 text-gray-400 text-sm">
            Nenhuma disponibilidade cadastrada.
        </div>
        <div x-show="loading" class="text-center py-8 text-gray-400 text-sm">
            Carregando...
        </div>
    </div>
</div>

<script>
function availabilitiesData() {
    return {
        availabilities: [],
        loading: false,
        dayLabels: { 0: 'Domingo', 1: 'Segunda-feira', 2: 'Terça-feira', 3: 'Quarta-feira', 4: 'Quinta-feira', 5: 'Sexta-feira', 6: 'Sábado' },
        async loadData() {
            this.loading = true;
            const { success, data } = await window.api('/availabilities');
            if (success) this.availabilities = data;
            this.loading = false;
        },
        getDayLabel(day) {
            return this.dayLabels[day] || '-';
        }
    }
}
</script>
@endsection
