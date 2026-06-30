@extends('layouts.app')

@section('content')
<div x-data="usersData()" x-init="loadData()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Usuários</h2>
            <p class="text-gray-500 text-sm">Gerenciamento de usuários do sistema</p>
        </div>
        <a x-show="window.getUser()?.profile === 'administrador'"
           href="/users/create"
           class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
            + Novo Usuário
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Nome</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">E-mail</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Perfil</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-600">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-for="user in users" :key="user.public_id">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3" x-text="user.name"></td>
                        <td class="px-4 py-3 text-gray-500" x-text="user.email"></td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                  :class="user.profile === 'administrador' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'"
                                  x-text="user.profile === 'administrador' ? 'Administrador' : 'Atendente'"></span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a :href="`/users/${user.public_id}/edit`"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Editar
                                </a>
                                <template x-if="window.getUser()?.profile === 'administrador'">
                                    <button @click="confirmDelete(user)"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Excluir
                                    </button>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div x-show="users.length === 0 && !loading" class="text-center py-8 text-gray-400 text-sm">
            Nenhum usuário encontrado.
        </div>
        <div x-show="loading" class="text-center py-8 text-gray-400 text-sm">
            Carregando...
        </div>
    </div>
</div>

<script>
function usersData() {
    return {
        users: [],
        loading: false,
        async loadData() {
            this.loading = true;
            const { success, data } = await window.api('/users');
            if (success) this.users = data;
            this.loading = false;
        },
        confirmDelete(user) {
            window.showModal(
                'Excluir Usuário',
                `Tem certeza que deseja excluir o usuário "${user.name}"? Esta ação não pode ser desfeita.`,
                async () => {
                    const { success } = await window.api(`/users/${user.public_id}`, { method: 'DELETE' });
                    window.closeModal();
                    if (success) {
                        window.showAlert('Usuário excluído com sucesso.', 'success');
                        await this.loadData();
                    }
                }
            );
        }
    }
}
</script>
@endsection
