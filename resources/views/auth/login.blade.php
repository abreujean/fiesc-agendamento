@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-bg-main">
    <div class="w-full max-w-md p-8">
        <div class="bg-white rounded-[10px] shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-primary mb-1">FIESC Agendamentos</h2>
                <p class="text-text-muted text-sm">Faça login para acessar o sistema</p>
            </div>

            <div x-data="loginForm()" x-init="checkAuth()">
                <form @submit.prevent="handleLogin()">
                    <div class="mb-4">
                        <x-forms.input name="email" label="E-mail" type="email" placeholder="seu@email.com" />
                    </div>

                    <div class="mb-6">
                        <x-forms.input name="password" label="Senha" type="password" placeholder="Sua senha" />
                    </div>

                    <x-buttons.submit label="Entrar" loadingLabel="Entrando..." fullWidth :showSpinner="true" />

                    <p x-show="errors.general" class="text-secondary text-xs mt-3 text-center" x-text="errors.general"></p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
