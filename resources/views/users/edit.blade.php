@extends('layouts.app')

@section('content')
<div x-data="editUserForm('{{ $user->public_id }}')">
    <x-headers.form-header title="Editar Usuário" subtitle="Atualize os dados do usuário" backUrl="/users" />

    <div class="bg-white rounded-[10px] shadow-sm p-6">
        <form @submit.prevent="submit()" class="space-y-4">
            <x-forms.input name="name" label="Nome" placeholder="Nome completo" />
            <x-forms.input name="profile" label="Tipo de Usuário" type="select"
                :options="[['value' => 'administrador', 'label' => 'Administrador'], ['value' => 'atendente', 'label' => 'Atendente']]" />

            <div class="flex justify-end gap-3 pt-2">
                <x-buttons.cancel href="/users" />
                <x-buttons.submit label="Salvar" loadingLabel="Salvando..." :showSpinner="true" />
            </div>
        </form>
    </div>
</div>
@endsection
