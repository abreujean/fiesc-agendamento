export function profileData() {
    return {
        appointments: [],
        loading: false,

        async init() {
            await this.loadAppointments();
        },

        async loadAppointments() {
            this.loading = true;
            const { success, data } = await window.api('/my-appointments');
            if (success) this.appointments = data;
            this.loading = false;
        },

        statusLabel(status) {
            const labels = { agendado: 'Agendado', cancelado: 'Cancelado', concluido: 'Concluído' };
            return labels[status] || status;
        },

        statusClass(status) {
            const classes = {
                agendado: 'bg-primary/10 text-primary',
                cancelado: 'bg-secondary/10 text-secondary',
                concluido: 'bg-accent/10 text-accent',
            };
            return classes[status] || 'bg-bg-main text-text-muted';
        },
    };
}
