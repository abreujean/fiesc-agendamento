<nav class="bg-white border-b border-border-main px-6 py-3 flex items-center justify-between">
    <h1 class="text-lg font-semibold text-primary">FIESC - Agendamentos</h1>
    <div class="flex items-center gap-4">
        <span class="text-sm text-text-muted">{{ auth()->user()?->name }}</span>
        <button @click="window.logout()" class="inline-flex items-center gap-1.5 text-sm text-secondary hover:text-secondary-light font-medium cursor-pointer transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
            Sair
        </button>
    </div>
</nav>
