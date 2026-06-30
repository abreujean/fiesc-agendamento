@extends('layouts.app')

@section('content')
<div x-data="createUserForm()" class="max-w-lg mx-auto">
    <x-headers.form-header title="Novo Usuário" subtitle="Preencha os campos abaixo para criar um novo usuário" backUrl="/users" />

    <div class="bg-white rounded-[10px] shadow-sm p-6">
        <form @submit.prevent="submit()" class="space-y-4">
            <x-forms.input name="name" label="Nome" placeholder="Nome completo" />
            <x-forms.input name="email" label="E-mail" type="email" placeholder="seu@email.com" />
            <x-forms.input name="password" label="Senha" type="password" placeholder="Mínimo 8 caracteres" />
            <x-forms.input name="password_confirmation" label="Confirme a Senha" type="password" placeholder="Repita a senha" />
            <x-forms.input name="profile" label="Tipo de Usuário" type="select"
                :options="[['value' => '', 'label' => 'Selecione...'], ['value' => 'administrador', 'label' => 'Administrador'], ['value' => 'atendente', 'label' => 'Atendente']]" />

            <div class="flex justify-end gap-3 pt-2">
                <x-buttons.cancel href="/users" />
                <x-buttons.submit label="Cadastrar" loadingLabel="Cadastrando..." :showSpinner="true" />
            </div>
        </form>
    </div>
</div>
@endsection
