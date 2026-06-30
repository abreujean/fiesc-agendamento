@extends('layouts.app')

@section('content')
<div x-data="createUserForm()" class="max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Novo Usuário</h2>
    <a href="/users" class="text-blue-600 hover:text-blue-800 text-sm mb-4 inline-block">&larr; Voltar</a>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form @submit.prevent="handleSubmit()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.name"
                       class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 :class="errors.name ? 'border-red-500' : 'border-gray-300'"
                       placeholder="Nome completo">
                <p x-show="errors.name" class="text-red-500 text-xs mt-1" x-text="errors.name"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail <span class="text-red-500">*</span></label>
                <input type="email" x-model="form.email"
                       class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 :class="errors.email ? 'border-red-500' : 'border-gray-300'"
                       placeholder="seu@email.com">
                <p x-show="errors.email" class="text-red-500 text-xs mt-1" x-text="errors.email"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Senha <span class="text-red-500">*</span></label>
                <input type="password" x-model="form.password"
                       class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 :class="errors.password ? 'border-red-500' : 'border-gray-300'"
                       placeholder="Mínimo 8 caracteres">
                <p x-show="errors.password" class="text-red-500 text-xs mt-1" x-text="errors.password"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirme a Senha <span class="text-red-500">*</span></label>
                <input type="password" x-model="form.password_confirmation"
                       class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 :class="errors.password_confirmation ? 'border-red-500' : 'border-gray-300'"
                       placeholder="Repita a senha">
                <p x-show="errors.password_confirmation" class="text-red-500 text-xs mt-1" x-text="errors.password_confirmation"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Usuário <span class="text-red-500">*</span></label>
                <select x-model="form.profile"
                        class="w-full px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Selecione...</option>
                    <option value="administrador">Administrador</option>
                    <option value="atendente">Atendente</option>
                </select>
                <p x-show="errors.profile" class="text-red-500 text-xs mt-1" x-text="errors.profile"></p>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="/users" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancelar</a>
                <button type="submit" :disabled="loading"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 disabled:opacity-50">
                    <span x-show="!loading">Cadastrar</span>
                    <span x-show="loading">Cadastrando...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function createUserForm() {
    return {
        form: { name: '', email: '', password: '', password_confirmation: '', profile: '' },
        errors: {},
        loading: false,
        handleSubmit() {
            this.errors = {};
            this.loading = true;

            window.api('/users', {
                method: 'POST',
                body: JSON.stringify(this.form),
            }).then(({ success, data, status }) => {
                this.loading = false;

                if (success) {
                    window.showAlert('Usuário criado com sucesso!', 'success');
                    window.location.href = '/users';
                } else if (status === 422 && data.errors) {
                    this.errors = Object.fromEntries(
                        Object.entries(data.errors).map(([field, messages]) => [field, messages[0]])
                    );
                } else {
                    this.errors = { general: data.message || 'Erro ao criar usuário.' };
                }
            });
        }
    }
}
</script>
@endsection
