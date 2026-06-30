@extends('layouts.app')

@section('content')
<div x-data="appointmentsData()">
    <x-headers.page-header title="Agendamentos" subtitle="Gerenciamento de agendamentos"
        actionLabel="Novo Agendamento" actionHref="/appointments/create" />

    <div class="bg-white rounded-[10px] shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-primary mb-4">Consultar Horários Disponíveis</h3>
        <div class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-text-main mb-1">Atendente <span class="text-secondary">*</span></label>
                <select x-model="filters.attendantId" @change="loadSlots()"
                        class="w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white cursor-pointer transition-colors duration-200">
                    <option value="">Selecione...</option>
                    <template x-for="user in attendants" :key="user.public_id">
                        <option :value="user.id" x-text="user.name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-1">Data <span class="text-secondary">*</span></label>
                <input type="date" x-model="filters.date" @change="loadSlots()" :min="todayLocal"
                       class="w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary cursor-pointer transition-colors duration-200">
            </div>
            <div x-show="filters.attendantId && filters.date" class="flex items-end">
                <x-buttons.submit label="Buscar Horários" onclick="loadSlots()" />
            </div>
        </div>

        <div x-show="slots.length > 0" class="mt-4">
            <h4 class="text-sm font-medium text-text-muted mb-2">
                Horários disponíveis (<span x-text="slots.length"></span> encontrado(s))
            </h4>
            <div class="flex flex-wrap gap-2">
                <template x-for="slot in slots" :key="slot.start_time + slot.end_time">
                    <button @click="selectSlot(slot)"
                            class="cursor-pointer px-4 py-3 bg-white border-2 border-border-main rounded-[10px] text-sm hover:border-primary hover:bg-primary/5 transition-colors duration-200"
                            :class="selectedSlot === slot ? 'border-primary bg-primary/5 text-primary font-medium' : ''">
                        <div class="font-semibold" x-text="slot.start_time + ' - ' + slot.end_time"></div>
                    </button>
                </template>
            </div>
        </div>
        <div x-show="filters.attendantId && filters.date && slots.length === 0 && !loading"
             class="mt-4 text-text-muted text-sm">
            Nenhum horário disponível para a data selecionada.
        </div>
    </div>

    <div x-show="selectedSlot" class="mt-8">
        <h3 class="text-lg font-semibold text-primary mb-4">Confirmar Agendamento</h3>
        <div class="bg-white rounded-[10px] shadow-sm p-6">
            <form @submit.prevent="handleBook()" class="space-y-4">
                <x-forms.input name="client_name" label="Nome do Cliente" placeholder="Nome do cliente" />

                <x-forms.input name="client_email" label="E-mail do Cliente" type="email"
                    placeholder="email@cliente.com (opcional)" :required="false" />

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
