<nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm">
    <h1 class="text-lg font-semibold text-gray-700">FIESC - Agendamentos</h1>
    <div class="flex items-center gap-4" x-data>
        <span class="text-sm text-gray-500" x-text="window.getUser()?.name || ''"></span>
        <button @click="window.logout()" class="text-sm text-red-600 hover:text-red-800 font-medium">
            Sair
        </button>
    </div>
</nav>
