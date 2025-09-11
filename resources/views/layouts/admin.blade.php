<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel Administrativo - DK Pandora')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6a0dad;
            --secondary-color: #00d4ff;
            --accent-color: #ff6b35;
            --dark-bg: #0f0f23;
            --card-bg: #1a1a2e;
            --text-light: #ffffff;
            --text-muted: #a0a0a0;
            --gradient-primary: linear-gradient(135deg, #6a0dad 0%, #8b5cf6 100%);
            --gradient-secondary: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);

            /* Sobrescrever variáveis do Bootstrap para tabelas */
            --bs-table-bg: #1a1a2e;
            --bs-table-color: #ffffff;
            --bs-table-hover-bg: rgba(106, 13, 173, 0.3);
            --bs-table-hover-color: #ffffff;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--dark-bg);
            color: var(--text-light);
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            background: var(--card-bg);
            min-height: 100vh;
            border-right: 1px solid rgba(106, 13, 173, 0.3);
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
        }

        .sidebar-header {
            background: var(--gradient-primary);
            padding: 1.5rem;
            text-align: center;
        }

        .sidebar-brand {
            color: white;
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(106, 13, 173, 0.2);
            color: var(--secondary-color);
        }

        .nav-link.active {
            background: var(--gradient-primary);
            color: white;
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        /* Header */
        .admin-header {
            background: var(--card-bg);
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(106, 13, 173, 0.3);
            margin-bottom: 2rem;
            border-radius: 10px;
        }

        .admin-header h1 {
            margin: 0;
            color: var(--text-light);
            font-weight: 700;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.3);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(106, 13, 173, 0.2) 0%, rgba(25, 25, 35, 0.2) 100%);
            border-bottom: 1px solid rgba(106, 13, 173, 0.3);
            border-radius: 15px 15px 0 0 !important;
            color: var(--text-light);
        }

        .card-body {
            color: var(--text-light);
        }

        /* Stats Cards */
        .stats-card {
            background: var(--gradient-primary);
            border: none;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            color: white;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card .icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .stats-card .number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-card .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Tables - Minimalista */
        .table {
            color: #ffffff;
            background: transparent;
            border: none;
            margin-bottom: 0;
        }

        /* Sobrescrever regras específicas do Bootstrap */
        .table > :not(caption) > * > * {
            background-color: transparent !important;
            color: #ffffff !important;
            border: none !important;
        }

        .table tbody tr:nth-child(even) > * {
            background-color: transparent !important;
        }

        .table tbody tr:nth-child(odd) > * {
            background-color: transparent !important;
        }

        .table thead th {
            background: transparent;
            color: #a0a0a0;
            border: none;
            font-weight: 500;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table tbody tr {
            background: transparent;
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table tbody tr:nth-child(even) {
            background: transparent;
        }

        .table tbody tr:hover {
            background: rgba(106, 13, 173, 0.1);
            transform: none;
            box-shadow: none;
        }

        .table tbody td {
            border: none;
            color: #ffffff;
            padding: 1rem;
            font-weight: 400;
            vertical-align: middle;
            font-size: 0.9rem;
            text-shadow: none;
        }

        .table tbody td:first-child {
            font-weight: 500;
            color: #00d4ff;
            font-size: 0.9rem;
        }

        .table tbody td:nth-child(2) {
            color: #ffffff;
            font-weight: 400;
        }

        .table tbody td:nth-child(3) {
            color: #28a745;
            font-weight: 400;
        }

        .table tbody td:nth-child(5) {
            color: #a0a0a0;
            font-size: 0.85rem;
        }

        /* Buttons */
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 13, 173, 0.4);
        }

        /* Botões de ação na tabela - Minimalista */
        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
            font-weight: 400;
            border-radius: 4px;
            text-transform: none;
            letter-spacing: 0;
            box-shadow: none;
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-sm:hover {
            transform: none;
            box-shadow: none;
            opacity: 0.8;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 25px;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 25px;
        }

        /* Forms */
        .form-control {
            background: var(--card-bg);
            border: 1px solid rgba(106, 13, 173, 0.3);
            color: var(--text-light);
            border-radius: 10px;
        }

        .form-control:focus {
            background: var(--card-bg);
            border-color: var(--primary-color);
            color: var(--text-light);
            box-shadow: 0 0 0 0.2rem rgba(106, 13, 173, 0.25);
        }

        .form-label {
            color: var(--text-light);
            font-weight: 500;
        }

        /* Status Badges */
        .badge-pending {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #000000;
            font-weight: 800;
            padding: 0.6rem 1rem;
            border-radius: 25px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
            border: 2px solid #ffb300;
        }
        .badge-processing {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: #ffffff;
            font-weight: 800;
            padding: 0.6rem 1rem;
            border-radius: 25px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
            border: 2px solid #138496;
        }
        .badge-completed {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: #ffffff;
            font-weight: 800;
            padding: 0.6rem 1rem;
            border-radius: 25px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
            border: 2px solid #1e7e34;
        }
        .badge-cancelled {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: #ffffff;
            font-weight: 800;
            padding: 0.6rem 1rem;
            border-radius: 25px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
            border: 2px solid #c82333;
        }

        /* Badge minimalista */
        .badge {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            text-transform: none;
            letter-spacing: 0;
            display: inline-block;
            min-width: auto;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="fas fa-dragon me-2"></i>DK Pandora
            </a>
            <small class="d-block mt-2 opacity-75">Painel Administrativo</small>
        </div>

        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    Produtos
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    Pedidos
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Usuários
                </a>
            </div>

            <hr class="my-3" style="border-color: rgba(106, 13, 173, 0.3);">

            <div class="nav-item">
                <a href="{{ route('admin.reports.sales') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    Relatórios
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    Configurações
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i class="fas fa-store"></i>
                    Ver Loja
                </a>
            </div>

            <div class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="admin-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="d-flex align-items-center">
                    <span class="me-3">
                        <i class="fas fa-user me-2"></i>
                        {{ Auth::user()->name }}
                    </span>
                    <span class="badge bg-primary">
                        <i class="fas fa-crown me-1"></i>
                        Administrador
                    </span>
                </div>
            </div>
        </div>

        <!-- Content -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    @yield('scripts')
</body>
</html>
