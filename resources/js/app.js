import Alpine from 'alpinejs';
import './api.js';
import { useFormSubmission, useDataLoader, useDeleteConfirmation } from './mixins.js';

function appLayout() {
    return {
        isActive(path) {
            return window.location.pathname === path ? 'bg-secondary text-white font-semibold' : '';
        }
    }
}

window.appLayout = appLayout;

Alpine.data('loginForm', () => ({
    ...useFormSubmission({ url: '/login', method: 'POST', successMessage: 'Login realizado!', redirectTo: '/' }),

    form: { email: '', password: '' },

    checkAuth() {
        if (window.getToken()) {
            window.location.href = '/';
        }
    },

    async handleLogin() {
        this.resetErrors();
        this.loading = true;

        const { success, data, status } = await window.api('/login', {
            method: 'POST',
            body: JSON.stringify(this.form),
        });

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
            this.errors = {};
            window.showAlert(data.message || 'Erro ao fazer login.', 'error');
        }
    }
}));

Alpine.data('dashboardData', () => ({
    stats: { appointmentsToday: 0, totalUsers: 0, totalAttendants: 0 },

    async init() {
        await this.loadStats();
    },

    async loadStats() {
        const { data: users } = await window.api('/users');
        const { data: appointments } = await window.api('/appointments');

        this.stats.totalUsers = users.length;
        this.stats.totalAttendants = users.filter(u => u.profile === 'atendente').length;
        this.stats.appointmentsToday = appointments.filter(a => {
            const today = new Date().toISOString().split('T')[0];
            return a.date === today;
        }).length;
    }
}));

Alpine.data('usersData', () => ({
    ...useDataLoader('/users', 'users'),
    ...useDeleteConfirmation((user) => `/users/${user.public_id}`),

    async init() {
        await this.loadData();
    },
}));

Alpine.data('createUserForm', () => ({
    ...useFormSubmission({ url: '/users', method: 'POST', successMessage: 'Usuário criado com sucesso!', redirectTo: '/users' }),

    form: { name: '', email: '', password: '', password_confirmation: '', profile: '' },
}));

Alpine.data('editUserForm', (publicId) => ({
    ...useFormSubmission({ url: `/users/${publicId}`, method: 'PUT', successMessage: 'Usuário atualizado com sucesso!', redirectTo: '/users' }),

    form: { name: '', profile: '' },

    async init() {
        const { success, data } = await window.api(`/users/${publicId}`);
        if (success) {
            this.form.name = data.name;
            this.form.profile = data.profile;
        }
    },
}));

Alpine.data('availabilitiesData', () => ({
    ...useDataLoader('/availabilities', 'availabilities'),

    dayLabels: { 0: 'Domingo', 1: 'Segunda-feira', 2: 'Terça-feira', 3: 'Quarta-feira', 4: 'Quinta-feira', 5: 'Sexta-feira', 6: 'Sábado' },

    async init() {
        await this.loadData();
    },

    getDayLabel(day) {
        return this.dayLabels[day] || '-';
    },
}));

Alpine.data('createAvailabilityForm', (userId, availabilityId) => ({
    ...useFormSubmission({
        url: availabilityId ? `/availabilities/${availabilityId}` : '/availabilities',
        method: availabilityId ? 'PUT' : 'POST',
        successMessage: availabilityId ? 'Disponibilidade atualizada!' : 'Disponibilidade criada!',
        redirectTo: '/availabilities',
    }),

    isEdit: !!availabilityId,
    form: { user_id: '', day_of_week: '', start_time: '', end_time: '', is_active: true },
    attendants: [],

    async init() {
        const { success, data } = await window.api('/users');
        if (success) {
            this.attendants = data.filter(u => u.profile === 'atendente');
        }
        if (!availabilityId) {
            this.form.user_id = userId;
        }
        if (availabilityId) {
            const { success: loaded, data: avail } = await window.api(`/availabilities/${availabilityId}`);
            if (loaded) {
                this.form = {
                    user_id: avail.user_id,
                    day_of_week: avail.day_of_week.toString(),
                    start_time: avail.start_time,
                    end_time: avail.end_time,
                    is_active: avail.is_active,
                };
            }
        }
    },
}));

Alpine.data('appointmentsData', () => ({
    users: [],
    attendants: [],
    slots: [],
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
        const user = this.users.find(u => u.id == this.filters.attendantId);
        return user ? user.name : '-';
    },

    async loadSlots() {
        if (!this.filters.attendantId || !this.filters.date) return;
        this.slots = [];
        this.loading = true;
        const { success, data } = await window.api(
            `/appointments/available-slots?attendant_id=${this.filters.attendantId}&date=${this.filters.date}`
        );
        if (success) this.slots = data.available_slots;
        this.loading = false;
        this.selectedSlot = null;
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
                attendant_id: parseInt(this.filters.attendantId),
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
    }
}));

window.Alpine = Alpine;
Alpine.start();
