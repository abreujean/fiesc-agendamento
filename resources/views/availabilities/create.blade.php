@extends('layouts.app')

@section('content')
<div x-data="createAvailabilityForm('{{ $availability?->public_id ?? '' }}')">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary mb-1" x-text="isEdit ? 'Editar Disponibilidade' : 'Nova Disponibilidade'"></h2>
        <p class="text-text-muted text-sm mb-4" x-text="isEdit ? 'Atualize a janela de horário' : 'Defina uma nova janela de horário para o atendente'"></p>
        <a href="/availabilities" class="inline-flex items-center gap-1 text-primary hover:text-primary-light text-sm cursor-pointer transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Voltar
        </a>
    </div>

    <div class="bg-white rounded-[10px] shadow-sm p-6">
        <form @submit.prevent="submit()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-text-main mb-1">Atendente <span class="text-secondary">*</span></label>
                <select x-model="form.user_id"
                        class="w-full px-3 py-2.5 border border-border-main rounded-[10px] text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white cursor-pointer transition-colors duration-200"
                        :disabled="isEdit"
                        :class="errors.user_id ? 'border-secondary ring-2 ring-secondary/20' : ''">
                    <option value="">Selecione o atendente...</option>
                    <template x-for="user in attendants" :key="user.public_id">
                        <option :value="user.public_id" x-text="user.name"></option>
                    </template>
                </select>
                <p x-show="errors.user_id" class="text-secondary text-xs mt-1" x-text="errors.user_id"></p>
            </div>

            <x-forms.input name="day_of_week" label="Dia da Semana" type="select"
                :options="[
                    ['value' => '', 'label' => 'Selecione o dia...'],
                    ['value' => '0', 'label' => 'Domingo'],
                    ['value' => '1', 'label' => 'Segunda-feira'],
                    ['value' => '2', 'label' => 'Terça-feira'],
                    ['value' => '3', 'label' => 'Quarta-feira'],
                    ['value' => '4', 'label' => 'Quinta-feira'],
                    ['value' => '5', 'label' => 'Sexta-feira'],
                    ['value' => '6', 'label' => 'Sábado'],
                ]" />

            <div class="grid grid-cols-2 gap-4">
                <x-forms.input name="start_time" label="Hora Inicial" type="time" />
                <x-forms.input name="end_time" label="Hora Final" type="time" />
            </div>

            <x-forms.input name="is_active" label="Ativo" type="checkbox" />

            <div class="flex justify-end gap-3 pt-2">
                <x-buttons.cancel href="/availabilities" />
                <x-buttons.submit label="Salvar" loadingLabel="Salvando..." :showSpinner="true" />
            </div>
        </form>
    </div>
</div>
@endsection
