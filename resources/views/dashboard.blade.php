@extends('layouts.app')

@section('content')
<div x-data="dashboardData()">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-gray-500 text-sm">Bem-vindo, <span x-text="window.getUser()?.name || ''"></span>!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <h3 class="text-3xl font-bold text-gray-800" x-text="stats.appointmentsToday">-</h3>
            <p class="text-gray-500 text-sm">Agendamentos Hoje</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <h3 class="text-3xl font-bold text-gray-800" x-text="stats.totalUsers">-</h3>
            <p class="text-gray-500 text-sm">Usuários Cadastrados</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <h3 class="text-3xl font-bold text-gray-800" x-text="stats.totalAttendants">-</h3>
            <p class="text-gray-500 text-sm">Atendantes</p>
        </div>
    </div>
</div>

<script>
function dashboardData() {
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
    }
}
</script>
@endsection
