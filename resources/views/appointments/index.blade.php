@extends('layouts.app')

@section('content')
<div x-data="appointmentsData()">
    <x-headers.page-header title="Agendamentos" subtitle="Gerenciamento de agendamentos" />

    <div class="bg-white rounded-[10px] shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-primary mb-4">Consultar Horários Disponíveis</h3>
        <div class="flex flex-wrap items-center gap-4">
            <div class="min-h-[100px]">
                <label class="block text-sm font-medium text-text-main mb-1">Atendente <span class="text-secondary">*</span></label>
                <select x-model="filters.attendantId" @change="delete errors.attendantId; loadSlots()"
                        class="w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white cursor-pointer transition-colors duration-200"
                        :class="errors.attendantId ? 'border-secondary ring-2 ring-secondary/20' : ''">
                    <option value="">Selecione...</option>
                    <template x-for="user in attendants" :key="user.public_id">
                        <option :value="user.public_id" x-text="user.name"></option>
                    </template>
                </select>
                <p x-show="errors.attendantId" x-cloak class="text-secondary text-xs mt-1 min-h-[16px]" x-text="errors.attendantId"></p>
            </div>
            <div class="min-h-[100px]">
                <label class="block text-sm font-medium text-text-main mb-1">Data <span class="text-secondary">*</span></label>
                <input type="date" x-model="filters.date" @change="delete errors.date; loadSlots()" :min="todayLocal"
                       class="w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary cursor-pointer transition-colors duration-200"
                       :class="errors.date ? 'border-secondary ring-2 ring-secondary/20' : ''">
                <p x-show="errors.date" x-cloak class="text-secondary text-xs mt-1 min-h-[16px]" x-text="errors.date"></p>
            </div>
            <div class="mb-3">
                <x-buttons.submit label="Buscar Horários" onclick="loadSlots()" />
            </div>
        </div>

        <div x-show="slots.length > 0" class="mt-4">
            <h4 class="text-sm font-medium text-text-muted mb-2">
                Horários disponíveis (<span x-text="availableSlots.length"></span> encontrado(s))
            </h4>
            <div class="flex flex-wrap gap-2">
                <template x-for="slot in slots" :key="slot.start_time + slot.end_time">
                    <template x-if="!slot.busy">
                        <button @click="selectSlot(slot)"
                                class="cursor-pointer px-4 py-3 bg-white border-2 border-border-main rounded-[10px] text-sm hover:border-primary hover:bg-primary/5 transition-colors duration-200"
                                :class="selectedSlot === slot ? 'border-primary bg-primary/5 text-primary font-medium' : ''">
                            <div class="font-semibold" x-text="slot.start_time + ' - ' + slot.end_time"></div>
                        </button>
                    </template>
                    <template x-if="slot.busy">
                        <div class="px-4 py-3 bg-bg-main border-2 border-border-main/50 rounded-[10px] text-sm opacity-50 cursor-not-allowed">
                            <div class="font-semibold text-text-muted" x-text="slot.start_time + ' - ' + slot.end_time"></div>
                            <div class="text-xs text-secondary">Ocupado</div>
                        </div>
                    </template>
                </template>
            </div>
        </div>
        <div x-show="filters.attendantId && filters.date && availableSlots.length === 0 && !loading"
             class="mt-4 text-text-muted text-sm">
            Nenhum horário disponível para a data selecionada.
        </div>
    </div>

    <div x-show="appointments.length > 0" class="mt-8">
        <h3 class="text-lg font-semibold text-primary mb-4">Agendamentos do Dia</h3>
        <div class="bg-white rounded-[10px] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-bg-main border-b border-border-main">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">Cliente</th>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">E-mail</th>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">Horário</th>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-main">
                        <template x-for="appt in appointments" :key="appt.public_id">
                            <tr class="hover:bg-bg-main transition-colors duration-200">
                                <td class="px-4 py-3 font-medium text-text-main" x-text="appt.client_name"></td>
                                <td class="px-4 py-3 text-text-muted" x-text="appt.client_email || '-'"></td>
                                <td class="px-4 py-3 text-text-main" x-text="appt.start_time + ' - ' + appt.end_time"></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                          :class="statusClass(appt.status)"
                                          x-text="statusLabel(appt.status)"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="selectedSlot" class="mt-8">
        <h3 class="text-lg font-semibold text-primary mb-4">Confirmar Agendamento</h3>
        <div class="bg-white rounded-[10px] shadow-sm p-6">
            <form @submit.prevent="handleBook()" class="space-y-4">
                <x-forms.input name="client_name" label="Nome do Cliente" placeholder="Nome do cliente" :model="'booking.client_name'" :errorKey="'client_name'" />

                <x-forms.input name="client_email" label="E-mail do Cliente" type="email"
                    placeholder="email@cliente.com (opcional)" :required="false" :model="'booking.client_email'" :errorKey="'client_email'" />

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Atendente</label>
                        <input type="text" :value="selectedAttendantName" readonly
                               class="w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm bg-bg-main text-text-muted">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-main mb-1">Horário</label>
                        <input type="text" :value="selectedSlot?.start_time + ' - ' + selectedSlot?.end_time" readonly
                               class="w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm bg-bg-main text-text-muted">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-main mb-1">Data</label>
                    <input type="text" :value="filters.date" readonly
                           class="w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm bg-bg-main text-text-muted">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <x-buttons.cancel onclick="cancelSlotSelection()" />
                    <x-buttons.submit label="Agendar" loadingLabel="Agendando..." color="accent" :showSpinner="true" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
