@extends('layouts.app')

@section('content')
<div x-data="createAppointmentForm()" x-init="loadData()">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Novo Agendamento</h2>
    <a href="/appointments" class="text-blue-600 hover:text-blue-800 text-sm mb-4 inline-block">&larr; Voltar</a>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Passo 1: Selecione o atendente e a data</h3>
        <div class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Atendente <span class="text-red-500">*</span></label>
                <select x-model="filters.attendantId"
                        @change="loadSlots()"
                        class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Selecione...</option>
                    <template x-for="user in attendants" :key="user.public_id">
                        <option :value="user.id" x-text="user.name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data <span class="text-red-500">*</span></label>
                <input type="date" x-model="filters.date" @change="loadSlots()" :min="todayLocal"
                       class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div x-show="slots.length > 0" class="mt-4">
            <h3 class="text-sm font-medium text-gray-700 mb-2">
                Selecione um horário (<span x-text="slots.length"></span> disponível(is))
            </h3>
            <div class="flex flex-wrap gap-2">
                <template x-for="slot in slots" :key="slot.start_time">
                    <button @click="selectSlot(slot)"
                            class="px-4 py-3 bg-white border-2 border-gray-200 rounded-lg text-sm hover:border-blue-400 hover:bg-blue-50 transition-colors"
                            :class="selectedSlot?.start_time === slot.start_time ? 'border-blue-500 bg-blue-50 text-blue-700' : ''">
                        <div class="font-semibold" x-text="slot.start_time + ' - ' + slot.end_time"></div>
                    </button>
                </template>
            </div>
        </div>

        <div x-show="selectedSlot" class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Passo 2: Dados do Cliente</h3>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form @submit.prevent="handleBook()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Cliente <span class="text-red-500">*</span></label>
                        <input type="text" x-model="booking.client_name"
                               class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @class="errors.client_name ? 'border-red-500' : 'border-gray-300'"
                               placeholder="Nome do cliente">
                        <p x-show="errors.client_name" class="text-red-500 text-xs mt-1" x-text="errors.client_name"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail do Cliente</label>
                        <input type="email" x-model="booking.client_email"
                               class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @class="errors.client_email ? 'border-red-500' : 'border-gray-300'"
                               placeholder="email@cliente.com (opcional)">
                        <p x-show="errors.client_email" class="text-red-500 text-xs mt-1" x-text="errors.client_email"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Atendente</label>
                            <input type="text" :value="selectedAttendantName" readonly
                                   class="w-full px-3 py-2 border rounded-md text-sm bg-gray-50 text-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Horário</label>
                            <input type="text" :value="selectedSlot.start_time + ' - ' + selectedSlot.end_time" readonly
                                   class="w-full px-3 py-2 border rounded-md text-sm bg-gray-50 text-gray-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                        <input type="text" :value="filters.date" readonly
                               class="w-full px-3 py-2 border rounded-md text-sm bg-gray-50 text-gray-500">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="selectedSlot = null"
                                class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Voltar
                        </button>
                        <button type="submit" :disabled="loading"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 disabled:opacity-50">
                            <span x-show="!loading">Agendar</span>
                            <span x-show="loading">Agendando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function createAppointmentForm() {
    return {
        users: [],
        attendants: [],
        slots: [],
        selectedSlot: null,
        filters: { attendantId: '', date: '' },
        booking: { client_name: '', client_email: '' },
        errors: {},
        loading: false,
        todayLocal: new Date().toISOString().split('T')[0],
        get selectedAttendantName() {
            if (!this.filters.attendantId) return '-';
            const user = this.users.find(u => u.id == this.filters.attendantId);
            return user ? user.name : '-';
        },
        async loadData() {
            const { success, data } = await window.api('/users');
            if (success) {
                this.users = data;
                this.attendants = data.filter(u => u.profile === 'atendente');
            }
        },
        async loadSlots() {
            if (!this.filters.attendantId || !this.filters.date) return;
            this.slots = [];
            this.loading = true;
            const { success, data } = await window.api(
                `/appointments/available-slots?attendant_id=${this.filters.attendantId}&date=${this.filters.date}`
            );
            if (success) this.slots = data.available_slots;
            this.loading = false;
            this.selectedSlot = null;
        },
        selectSlot(slot) {
            this.selectedSlot = slot;
            this.booking = { client_name: '', client_email: '' };
            this.errors = {};
        },
        handleBook() {
            this.errors = {};
            this.loading = true;

            window.api('/appointments', {
                method: 'POST',
                body: JSON.stringify({
                    attendant_id: parseInt(this.filters.attendantId),
                    client_name: this.booking.client_name,
                    client_email: this.booking.client_email || null,
                    date: this.filters.date,
                    start_time: this.selectedSlot.start_time,
                    end_time: this.selectedSlot.end_time,
                }),
            }).then(({ success, data, status }) => {
                this.loading = false;
                if (success) {
                    window.showAlert('Agendamento criado com sucesso!', 'success');
                    this.selectedSlot = null;
                    this.booking = { client_name: '', client_email: '' };
                    this.loadSlots();
                } else if (status === 422 && data.errors) {
                    this.errors = Object.fromEntries(
                        Object.entries(data.errors).map(([field, messages]) => [field, messages[0]])
                    );
                } else {
                    this.errors = { general: data.message || 'Erro ao criar agendamento.' };
                }
            });
        }
    }
}
</script>
@endsection
