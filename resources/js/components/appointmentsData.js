export function appointmentsData() {
    return {
        users: [],
        attendants: [],
        slots: [],
        appointments: [],
        selectedSlot: null,
        filters: { attendantId: '', date: '' },
        booking: { client_name: '', client_email: '' },
        errors: {},
        loading: false,
        todayLocal: new Date().toISOString().split('T')[0],

        async init() {
            await this.loadUsers();
        },

        async loadUsers() {
            const { success, data } = await window.api('/users');
            if (success) {
                this.users = data;
                this.attendants = data.filter(u => u.profile === 'atendente');
            }
        },

        get selectedAttendantName() {
            if (!this.filters.attendantId) return '-';
            const user = this.users.find(u => u.public_id === this.filters.attendantId);
            return user ? user.name : '-';
        },

        get availableSlots() {
            return this.slots.filter(s => !s.busy);
        },

        async loadSlots() {
            this.errors = {};

            if (!this.filters.attendantId) {
                this.errors.attendantId = 'Selecione um atendente.';
                return;
            }
            if (!this.filters.date) {
                this.errors.date = 'Selecione uma data.';
                return;
            }

            this.slots = [];
            this.appointments = [];
            this.loading = true;
            this.selectedSlot = null;
            const { success, data } = await window.api(
                `/appointments/available-slots?attendant_id=${this.filters.attendantId}&date=${this.filters.date}`
            );
            if (success) {
                this.slots = data.slots;
                this.appointments = data.appointments;
            }
            this.loading = false;
        },

        selectSlot(slot) {
            this.selectedSlot = slot;
            this.booking = { client_name: '', client_email: '' };
            this.errors = {};
        },

        cancelSlotSelection() {
            this.selectedSlot = null;
            this.errors = {};
        },

        async handleBook() {
            this.errors = {};
            this.loading = true;

            const { success, data, status } = await window.api('/appointments', {
                method: 'POST',
                body: JSON.stringify({
                    attendant_id: this.filters.attendantId,
                    client_name: this.booking.client_name,
                    client_email: this.booking.client_email || null,
                    date: this.filters.date,
                    start_time: this.selectedSlot.start_time,
                    end_time: this.selectedSlot.end_time,
                }),
            });

            this.loading = false;

            if (success) {
                window.showAlert('Agendamento criado com sucesso!', 'success');
                this.selectedSlot = null;
                this.booking = { client_name: '', client_email: '' };
                this.loadSlots();
            } else if (status === 422 && data.errors) {
                this.errors = Object.fromEntries(
                    Object.entries(data.errors).map(([field, messages]) => [field, messages[0]])
                );
            } else {
                this.errors = {};
                window.showAlert(data.message || 'Erro ao criar agendamento.', 'error');
            }
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
