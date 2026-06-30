<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FIESC - Agendamentos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (!localStorage.getItem('auth_token') && !window.location.pathname.includes('/login')) {
            window.location.href = '/login';
        }
    </script>
</head>
<body class="bg-bg-main text-text-main min-h-screen">

    @if(request()->is('login'))
        @yield('content')
    @else
        <div class="flex h-screen" x-data="appLayout()">
            @include('partials.sidebar')
            <div class="flex-1 flex flex-col">
                @include('partials.navbar')
                <main class="flex-1 p-6 overflow-auto">
                    @yield('content')
                </main>
            </div>
        </div>
    @endif

    <x-alerts.toast />
    @include('partials.modal')

</body>
</html>
