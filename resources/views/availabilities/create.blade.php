@extends('layouts.app')

@section('content')
<div x-data="createAvailabilityForm('{{ $user_id ?? '' }}', '{{ $availability?->public_id ?? '' }}')" x-init="loadIfEdit()">
    <h2 class="text-2xl font-bold text-gray-800 mb-6" x-text="isEdit ? 'Editar Disponibilidade' : 'Nova Disponibilidade'"></h2>
    <a href="/availabilities" class="text-blue-600 hover:text-blue-800 text-sm mb-4 inline-block">&larr; Voltar</a>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form @submit.prevent="handleSubmit()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Atendente <span class="text-red-500">*</span></label>
                <select x-model="form.user_id"
                        class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                        :disabled="isEdit">
                    <option value="">Selecione o atendente...</option>
                    <template x-for="user in attendants" :key="user.public_id">
                        <option :value="user.id" x-text="user.name"></option>
                    </template>
                </select>
                <p x-show="errors.user_id" class="text-red-500 text-xs mt-1" x-text="errors.user_id"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dia da Semana <span class="text-red-500">*</span></label>
                <select x-model="form.day_of_week"
                        class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Selecione o dia...</option>
                    <option value="0">Domingo</option>
                    <option value="1">Segunda-feira</option>
                    <option value="2">Terça-feira</option>
                    <option value="3">Quarta-feira</option>
                    <option value="4">Quinta-feira</option>
                    <option value="5">Sexta-feira</option>
                    <option value="6">Sábado</option>
                </select>
                <p x-show="errors.day_of_week" class="text-red-500 text-xs mt-1" x-text="errors.day_of_week"></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora Inicial <span class="text-red-500">*</span></label>
                    <input type="time" x-model="form.start_time"
                           class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 :class="errors.start_time ? 'border-red-500' : 'border-gray-300'">
                    <p x-show="errors.start_time" class="text-red-500 text-xs mt-1" x-text="errors.start_time"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora Final <span class="text-red-500">*</span></label>
                    <input type="time" x-model="form.end_time"
                           class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 :class="errors.end_time ? 'border-red-500' : 'border-gray-300'">
                    <p x-show="errors.end_time" class="text-red-500 text-xs mt-1" x-text="errors.end_time"></p>
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" x-model="form.is_active"
                           class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Ativo</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="/availabilities" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancelar</a>
                <button type="submit" :disabled="loading"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 disabled:opacity-50">
                    <span x-show="!loading" x-text="isEdit ? 'Salvar' : 'Cadastrar'"></span>
                    <span x-show="loading">Salvando...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function createAvailabilityForm(userId, availabilityId) {
    return {
        isEdit: !!availabilityId,
        form: { user_id: '', day_of_week: '', start_time: '', end_time: '', is_active: true },
        errors: {},
        loading: false,
        attendants: [],
        async loadIfEdit() {
            if (!availabilityId) return;
            const { success, data } = await window.api(`/availabilities/${availabilityId}`);
            if (success) {
                this.form = {
                    user_id: data.user_id,
                    day_of_week: data.day_of_week.toString(),
                    start_time: data.start_time,
                    end_time: data.end_time,
                    is_active: data.is_active,
                };
            }
        },
        async init() {
            const { success, data } = await window.api('/users');
            if (success) {
                this.attendants = data.filter(u => u.profile === 'atendente');
            }
            if (!availabilityId) {
                this.form.user_id = userId;
            }
        },
        handleSubmit() {
            this.errors = {};
            this.loading = true;

            const url = this.isEdit ? `/availabilities/${availabilityId}` : '/availabilities';
            const method = this.isEdit ? 'PUT' : 'POST';

            window.api(url, {
                method,
                body: JSON.stringify(this.form),
            }).then(({ success, data, status }) => {
                this.loading = false;

                if (success) {
                    window.showAlert(this.isEdit ? 'Disponibilidade atualizada!' : 'Disponibilidade criada!', 'success');
                    window.location.href = '/availabilities';
                } else if (status === 422 && data.errors) {
                    this.errors = Object.fromEntries(
                        Object.entries(data.errors).map(([field, messages]) => [field, messages[0]])
                    );
                } else {
                    this.errors = { general: data.message || 'Erro ao salvar.' };
                }
            });
        }
    }
}
</script>
@endsection
