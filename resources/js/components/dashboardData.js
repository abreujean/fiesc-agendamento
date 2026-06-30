export function dashboardData() {
    return {
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
    };
}
