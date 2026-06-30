@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md p-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">FIESC - Agendamentos</h2>
            <p class="text-center text-gray-500 text-sm mb-8">Faça login para acessar o sistema</p>

            <div x-data="loginForm()" x-init="checkAuth()">
                <form @submit.prevent="handleLogin()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail <span class="text-red-500">*</span></label>
                        <input type="email" x-model="form.email"
                               class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" :class="errors.email ? 'border-red-500' : 'border-gray-300'"
                               placeholder="seu@email.com">
                        <p x-show="errors.email" class="text-red-500 text-xs mt-1" x-text="errors.email"></p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Senha <span class="text-red-500">*</span></label>
                        <input type="password" x-model="form.password"
                               class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" :class="errors.password ? 'border-red-500' : 'border-gray-300'"
                               placeholder="Sua senha">
                        <p x-show="errors.password" class="text-red-500 text-xs mt-1" x-text="errors.password"></p>
                    </div>

                    <button type="submit" :disabled="loading"
                            class="w-full py-2 px-4 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 disabled:opacity-50 flex items-center justify-center">
                        <span x-show="!loading">Entrar</span>
                        <svg x-show="loading" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-show="loading">Entrando...</span>
                    </button>

                    <p x-show="errors.general" class="text-red-500 text-xs mt-3 text-center" x-text="errors.general"></p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loginForm() {
    return {
        form: { email: '', password: '' },
        errors: {},
        loading: false,
        checkAuth() {
            if (window.getToken()) {
                window.location.href = '/';
            }
        },
        handleLogin() {
            this.errors = {};
            this.loading = true;

            window.api('/login', {
                method: 'POST',
                body: JSON.stringify(this.form),
            }).then(({ success, data, status }) => {
                this.loading = false;

                if (success) {
                    window.setToken(data.token);
                    window.setUser(data.user);
                    window.location.href = '/';
                } else if (status === 422 && data.errors) {
                    this.errors = Object.fromEntries(
                        Object.entries(data.errors).map(([field, messages]) => [field, messages[0]])
                    );
                } else {
                    this.errors = { general: data.message || 'Erro ao fazer login.' };
                }
            });
        }
    }
}
</script>
@endsection
