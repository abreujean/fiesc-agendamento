<div x-data="modalAndAlerts()" x-cloak>

    <div x-show="showModal"
         class="fixed inset-0 z-50 flex items-center justify-center"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/50" @click="closeModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h3 class="text-lg font-semibold text-gray-800 mb-4" x-text="modalTitle"></h3>
            <div class="text-gray-600 text-sm mb-6" x-text="modalBody"></div>
            <div class="flex justify-end gap-3">
                <button @click="closeModal()" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancelar
                </button>
                <button @click="confirmAction && confirmAction()"
                        :disabled="confirmLoading"
                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50">
                    <span x-text="confirmLoading ? 'Aguarde...' : 'Confirmar'"></span>
                </button>
            </div>
        </div>
    </div>

    <div class="fixed top-4 right-4 z-[60] flex flex-col gap-2 items-end pointer-events-none">
        <template x-for="(alert, index) in alerts" :key="index">
            <div class="pointer-events-auto rounded-lg shadow-lg px-4 py-3 text-sm max-w-sm border"
                 :class="alert.type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'"
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
    if (!el || !el.__x) return;
    const data = el.__x.$data;
    data.modalTitle = title;
    data.modalBody = body;
    data.confirmAction = action;
    data.confirmLoading = false;
    data.showModal = true;
};

window.closeModal = function() {
    const el = document.querySelector('[x-data="modalAndAlerts()"]');
    if (!el || !el.__x) return;
    el.__x.$data.closeModal();
};

window.showAlert = function(message, type = 'error') {
    const el = document.querySelector('[x-data="modalAndAlerts()"]');
    if (!el || !el.__x) return;
    el.__x.$data.alerts.push({ message, type, visible: true });
};
</script>
