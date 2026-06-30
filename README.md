# FIESC Agendamentos

Sistema de gerenciamento de agendamentos entre clientes e atendentes. Desenvolvido como teste técnico para a vaga Full Stack Pleno na FIESC.

## Como Rodar

### Pré-requisitos

- Docker e Docker Compose
- Node.js 18+

### Instalação

```bash
# 1. Subir containers (MySQL + PHP)
./vendor/bin/sail up -d

# 2. Instalar dependências PHP
./vendor/bin/sail composer install

# 3. Instalar dependências Node
npm install

# 4. Copiar arquivo de ambiente
cp .env.example .env

# 5. Gerar key do Laravel
./vendor/bin/sail artisan key:generate

# 6. Rodar migrações e seeders
./vendor/bin/sail artisan migrate:fresh --seed

# 7. Build dos assets (produção)
npm run build

# OU usar Vite dev server (desenvolvimento com hot reload)
npm run dev
```

### Acessar

| Serviço | URL |
|---|---|
| Aplicação | http://localhost:8087 |
| Vite Dev Server | http://localhost:5173 |

## Credenciais

| Perfil | E-mail | Senha |
|---|---|---|
| Administrador | admin@fiesc.com | 12345678 |
| Atendente | joao@fiesc.com | 12345678 |
| Atendente | maria@fiesc.com | 12345678 |

## Dados do Seeder

### Usuários (3)

| Nome | Perfil |
|---|---|
| Administrador | Administrador |
| Atendente João | Atendente |
| Atendente Maria | Atendente |

### Disponibilidades (4)

| Atendente | Dia | Horário |
|---|---|---|
| Atendente João | Segunda-feira | 08:00 - 12:00 |
| Atendente João | Segunda-feira | 14:00 - 18:00 |
| Atendente João | Terça-feira | 08:00 - 12:00 |
| Atendente Maria | Segunda-feira | 09:00 - 17:00 |

## Funcionalidades

### Autenticação
- Login via Sanctum SPA Cookie (session-based, sem token manual)
- Middleware `auth` protege todas as rotas (server-side redirect para `/login`)
- Logout invalida sessão e cookie CSRF

### Dashboard
- Cards com totais: agendamentos do dia, total de usuários, total de atendentes
- Dados carregados via API

### Usuários (CRUD)
- **Administrador**: criar, listar, editar, excluir
- **Atendente**: listar, editar (próprios dados)
- Validação de perfil: atendente não pode alterar seu perfil para administrador
- Identificador público: `public_id` (UUID) em vez de auto-increment

### Disponibilidade
- **Criar**: selecionar atendente, dia da semana, horário de início/fim, status ativo
- **Editar**: alterar dia da semana, horários, status ativo (atendente não pode ser alterado)
- **Listar**: tabela com todos os registros
- **Validação de conflito**: impede criar/editar horários sobrepostos para o mesmo atendente no mesmo dia (ex: João segunda 08:00-12:00 + novo 10:00-13:00 = conflito)
- Mensagem de erro via toast alert

### Agendamentos
- **Consultar horários**: selecionar atendente + data → exibe slots de 30 minutos
- **Slots ocupados**: marcados como "Ocupado" (desabilitados) com ícone visual
- **Agendar**: preencher nome e e-mail do cliente, confirmar
- **Agendamentos do dia**: tabela abaixo dos slots com status (Agendado, Cancelado, Concluído)
- **Validação**: data deve ser hoje ou futura, horário deve estar dentro de disponibilidade ativa
- **Conflito**: impede agendar em horário já ocupado

### Alertas (Toast)
- Componente `<x-alerts.toast>` presente em todas as páginas
- Ícone + mensagem + botão fechar
- Verde (sucesso) / Vermelho (erro)
- Animação slide-in, desaparece após 4 segundos
- Aparece em: login, CRUD de usuários, CRUD de disponibilidades, agendamentos

## Arquitetura

### Stack

| Camada | Tecnologia | Versão |
|---|---|---|
| Runtime | PHP via Docker (Laravel Sail) | 8.4 |
| Framework | Laravel | 13 |
| Banco | MySQL | 8.4 |
| CSS | Tailwind CSS | 4 |
| JS | Alpine.js | 3.15 |
| Build | Vite | 8 |
| Auth | Laravel Sanctum (SPA Cookie) | - |

### Por que esta stack?

- **Laravel**: ecossistema completo (ORM, migrations, seeding, Sanctum, middleware, Blade) — ideal para CRUDs com regras de negócio
- **PHP 8.4**: requisitos da vaga
- **MySQL via Sail**: ambiente Docker padronizado, sem dependência local
- **Tailwind CSS 4**: utility-first com `@theme` para paleta customizada, sem configuração extra
- **Alpine.js**: reatividade sem overhead de frameworks SPA (React/Vue), ideal para Blade templates que precisam de interatividade pontual
- **Vite**: HMR rápido para desenvolvimento frontend
- **Sanctum SPA Cookie**: autenticação via session cookie (não expõe token no localStorage), `auth()->user()` funciona no PHP, middleware `auth` protege rotas server-side, cookie `httpOnly` mais seguro contra XSS

### Back-end

```
app/
├── Controllers/      # 4 controllers — delegam para Services
├── Services/         # 4 services — lógica de negócio
├── Models/           # 3 models (User, Availability, Appointment)
├── Enums/            # 3 enums (UserProfile, AppointmentStatus, DayOfWeekEnum)
├── Traits/           # HasUuid (UUID generation + route binding)
├── Middleware/       # AdminMiddleware (403 para não-admin)
└── Requests/         # 6 FormRequests (validação + mensagens PT-BR)
```

**Padrão**: Controller → Service → Model. Controller não contém lógica de negócio. FormRequest valida dados e retorna mensagens em português. Services retornam `JsonResponse` com HTTP codes.

**Rotas API** (`routes/api.php`): protegidas por `web` + `auth:sanctum` (session cookie). Rotas de criação/exclusão têm middleware `admin` adicional.

**Rotas Web** (`routes/web.php`): servem views Blade. Rotas protegidas por `web` + `auth` middleware (server-side, redireciona para login).

### Front-end

```
resources/
├── js/
│   ├── app.js              # Boot: imports + Alpine.data() + Alpine.start()
│   ├── api.js              # Fetch wrapper (credentials: same-origin)
│   ├── mixins.js            # useFormData, useDataLoader, useDeleteConfirmation
│   └── components/         # 1 arquivo por funcionalidade
│       ├── loginForm.js
│       ├── dashboardData.js
│       ├── usersData.js
│       ├── createUserForm.js
│       ├── editUserForm.js
│       ├── availabilitiesData.js
│       ├── createAvailabilityForm.js
│       ├── appointmentsData.js
│       └── alertsData.js
├── views/
│   ├── layouts/app.blade.php
│   ├── partials/           # sidebar, navbar, modal
│   ├── components/         # Blade components reutilizáveis
│   │   ├── forms/input     # x-model dinâmico, validação inline
│   │   ├── buttons/        # submit, cancel
│   │   ├── tables/         # data-table
│   │   ├── badges/         # status
│   │   ├── alerts/         # toast
│   │   ├── headers/        # page-header, form-header
│   │   ├── nav/            # sidebar-link
│   │   ├── cards/          # stat-card
│   │   └── states/         # list-state
│   └── [pages]             # auth/, users/, availabilities/, appointments/
```

**Padrão**: 1 Alpine component (`Alpine.data()`) por view. Mixins reutilizam lógica (form submission, data loading, delete confirmation). Blade components com props dinâmicas (`:model`, `:errorKey`).

**Fluxo de dados**: View Blade → Alpine component (`x-data`) → `apiFetch()` (fetch com cookie) → API Laravel → Service → Model → JSON response → Alpine atualiza DOM.

### Segurança

- **UUID público**: `public_id` em todas as entidades, auto-increment `id` hidden no JSON
- **Session cookie**: Sanctum SPA Cookie com `httpOnly`, `sameSite=lax`, CSRF protection
- **Server-side auth**: middleware `auth` nas rotas web, `auth:sanctum` nas rotas API
- **Admin middleware**: 403 para operações administrativas por atendentes
- **Validação**: FormRequests com regras e mensagens em português
- **Conflito de horários**: validação no `AvailabilityService::store()` e `update()`
