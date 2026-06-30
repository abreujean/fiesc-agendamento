@extends('layouts.app')

@section('content')
<div x-data="dashboardData()">
    <x-headers.page-header title="Dashboard" :subtitle="'Bem-vindo, ' . (auth()->user()->name ?? '') . '!'" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-cards.stat-card value="stats.appointmentsToday" label="Agendamentos Hoje" color="secondary">
            <x:slot:icon>
                <svg class="w-6 h-6 text-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
            </x:slot:icon>
        </x-cards.stat-card>

        <x-cards.stat-card value="stats.totalUsers" label="Usuários Cadastrados" color="primary">
            <x:slot:icon>
                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
            </x:slot:icon>
        </x-cards.stat-card>

        <x-cards.stat-card value="stats.totalAttendants" label="Atendentes" color="accent">
            <x:slot:icon>
                <svg class="w-6 h-6 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
            </x:slot:icon>
        </x-cards.stat-card>
    </div>
</div>
@endsection
