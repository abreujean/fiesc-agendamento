<aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm">
    <div class="p-4 border-b border-gray-200">
        <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wide">Menu</h2>
    </div>
    <nav class="flex-1 p-2">
        <a href="/" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100" :class="$data.isActive('/')">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-2l-2-2m-2 2l-10-10-10 2m2 2l10 10"/></svg>
            Dashboard
        </a>
        <a href="/users" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100" :class="$data.isActive('/users')">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8 4 4 0 010-8zM12 14a9 9 0 100-18 9 9 0 000 18z"/></svg>
            Usuários
        </a>
        <template x-if="window.getUser()?.profile === 'administrador'">
            <a href="/availabilities" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100" :class="$data.isActive('/availabilities')">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4l10-5 2 15H17l-2 5m-11-3v5h4"/></svg>
                Disponibilidade
            </a>
        </template>
        <a href="/appointments" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100" :class="$data.isActive('/appointments')">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4l10-5 2 15H17l-2 5m-11-3v5h4"/></svg>
            Agendamentos
        </a>
    </nav>
    <div class="p-3 border-t border-gray-200 text-xs text-gray-400">
        <span x-text="window.getUser()?.profile === 'administrador' ? 'Administrador' : 'Atendente'"></span>
    </div>
</aside>
