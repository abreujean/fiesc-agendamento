<div x-data="modalAndAlerts()" x-cloak>

    <div x-show="showModal"
         class="fixed inset-0 z-50 flex items-center justify-center"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="relative bg-white rounded-[10px] shadow-xl max-w-md w-full mx-4 p-6"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h3 class="text-lg font-semibold text-primary mb-4" x-text="modalTitle"></h3>
            <div class="text-text-muted text-sm mb-6" x-text="modalBody"></div>
            <div class="flex justify-end gap-3">
                <button @click="closeModal()" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm text-text-main bg-bg-main rounded-[10px] hover:bg-border-main cursor-pointer transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    Cancelar
                </button>
                <button @click="confirmAction && confirmAction()"
                        :disabled="confirmLoading"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm text-white bg-secondary rounded-[10px] hover:bg-secondary-light disabled:opacity-50 cursor-pointer transition-colors duration-200">
                    <svg x-show="!confirmLoading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    <span x-text="confirmLoading ? 'Aguarde...' : 'Confirmar'"></span>
                </button>
            </div>
        </div>
    </div>

    <div class="fixed top-4 right-4 z-[60] flex flex-col gap-2 items-end pointer-events-none">
        <template x-for="(alert, index) in alerts" :key="index">
            <div class="pointer-events-auto rounded-[10px] shadow-lg px-4 py-3 text-sm max-w-sm border"
                 :class="alert.type === 'success' ? 'bg-primary/5 border-primary/20 text-primary' : 'bg-secondary/5 border-secondary/20 text-secondary'"
                 x-show="alert.visible"
                 x-transition.opacity.duration.300ms
                 x-init="setTimeout(() => { alert.visible = false; setTimeout(() => alerts.splice(index, 1), 300); }, 4000)">
                <span x-text="alert.message"></span>
            </div>
        </template>
    </div>
</div>

<script>
function modalAndAlerts() {
    return {
        showModal: false,
        modalTitle: '',
        modalBody: '',
        confirmAction: null,
        confirmLoading: false,
        alerts: [],
        closeModal() {
            this.showModal = false;
            this.confirmAction = null;
            this.confirmLoading = false;
        }
    }
}

window.showModal = function(title, body, action) {
    const el = document.querySelector('[x-data="modalAndAlerts()"]');
    if (!el) return;
    const data = Alpine.$data(el);
    data.modalTitle = title;
    data.modalBody = body;
    data.confirmAction = action;
    data.confirmLoading = false;
    data.showModal = true;
};

window.closeModal = function() {
    const el = document.querySelector('[x-data="modalAndAlerts()"]');
    if (!el) return;
    Alpine.$data(el).closeModal();
};

window.showAlert = function(message, type = 'error') {
    const el = document.querySelector('[x-data="modalAndAlerts()"]');
    if (!el) return;
    Alpine.$data(el).alerts.push({ message, type, visible: true });
};
</script>
